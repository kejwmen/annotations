<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler;

use Doctrine\Annotations\Metadata\MetadataCollection;
use Doctrine\Annotations\Parser\Ast\Annotation;
use Doctrine\Annotations\Parser\Ast\Annotations;
use Doctrine\Annotations\Parser\Ast\Collection\ListCollection;
use Doctrine\Annotations\Parser\Ast\Collection\MapCollection;
use Doctrine\Annotations\Parser\Ast\ConstantFetch;
use Doctrine\Annotations\Parser\Ast\Node;
use Doctrine\Annotations\Parser\Ast\Pair;
use Doctrine\Annotations\Parser\Ast\Parameter\NamedParameter;
use Doctrine\Annotations\Parser\Ast\Parameter\UnnamedParameter;
use Doctrine\Annotations\Parser\Ast\Parameters;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Ast\Scalar\BooleanScalar;
use Doctrine\Annotations\Parser\Ast\Scalar\FloatScalar;
use Doctrine\Annotations\Parser\Ast\Scalar\Identifier;
use Doctrine\Annotations\Parser\Ast\Scalar\IntegerScalar;
use Doctrine\Annotations\Parser\Ast\Scalar\NullScalar;
use Doctrine\Annotations\Parser\Ast\Scalar\StringScalar;
use Doctrine\Annotations\Parser\Reference\ReferenceResolver;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Annotations\Parser\Visitor\Visitor;
use ReflectionClass;
use SplStack;
use function array_key_exists;
use function assert;
use function constant;

final class Assembler implements Visitor
{
    /** @var MetadataCollection */
    private $metadataCollection;

    /** @var ReferenceResolver */
    private $referenceResolver;

    /** @var ConstructorStrategy */
    private $constructorStrategy;

    /** @var PropertyStrategy */
    private $propertyStrategy;

    /** @var Scope */
    private $scope;

    /** @var object[] */
    private $annotations = [];

    /** @var SplStack */
    private $stack;

    public function __construct(
        MetadataCollection $metadataCollection,
        ReferenceResolver $referenceResolver,
        ConstructorStrategy $constructorStrategy,
        PropertyStrategy $propertyStrategy,
        Scope $scope
    ) {
        $this->metadataCollection  = $metadataCollection;
        $this->referenceResolver   = $referenceResolver;
        $this->constructorStrategy = $constructorStrategy;
        $this->propertyStrategy    = $propertyStrategy;
        $this->scope               = $scope;
    }

    /**
     * @return object[]
     */
    public function collect() : iterable
    {
        return $this->annotations;
    }

    public function visit(Node $node) : void
    {
        $this->annotations = [];
        $this->stack       = new SplStack();

        $node->dispatch($this);
    }

    public function visitAnnotations(Annotations $annotations) : void
    {
        foreach ($annotations as $annotation) {
            $annotation->dispatch($this);

            $this->annotations[] = $this->stack->pop();
        }
    }

    public function visitAnnotation(Annotation $annotation) : void
    {
        // TODO refactor out
        if (in_array($annotation->getName()->getIdentifier(), ['author', 'since', 'var'], true)) {
            return;
        }

        $annotation->getParameters()->dispatch($this);
        $annotation->getName()->dispatch($this);

        $this->stack->push($this->construct($this->stack->pop(), $this->stack->pop()));
    }

    public function visitReference(Reference $reference) : void
    {
        $this->stack->push($this->referenceResolver->resolve($reference, $this->scope));
    }

    public function visitParameters(Parameters $parameters) : void
    {
        $new = [];

        foreach ($parameters as $parameter) {
            $parameter->dispatch($this);

            assert(!array_key_exists($this->stack->current(), $new));

            $new[$this->stack->pop()] = $this->stack->pop();
        }

        $this->stack->push($new);
    }

    public function visitUnnamedParameter(UnnamedParameter $parameter) : void
    {
        $parameter->getValue()->dispatch($this);

        $this->stack->push('value');
        $this->stack->push($this->stack->pop());
    }

    public function visitNamedParameter(NamedParameter $parameter) : void
    {
        $parameter->getValue()->dispatch($this);
        $parameter->getName()->dispatch($this);

        // pass through
    }

    public function visitIdentifier(Identifier $identifier) : void
    {
        $this->stack->push($identifier->getValue());
    }

    public function visitPair(Pair $pair) : void
    {
        $pair->getValue()->dispatch($this);
        $pair->getKey()->dispatch($this);

        // pass through
    }

    public function visitBooleanScalar(BooleanScalar $booleanScalar) : void
    {
        $this->stack->push($booleanScalar->getValue());
    }

    public function visitIntegerScalar(IntegerScalar $integerScalar) : void
    {
        $this->stack->push($integerScalar->getValue());
    }

    public function visitFloatScalar(FloatScalar $floatScalar) : void
    {
        $this->stack->push($floatScalar->getValue());
    }

    public function visitStringScalar(StringScalar $stringScalar) : void
    {
        $this->stack->push($stringScalar->getValue());
    }

    public function visitNullScalar(NullScalar $nullScalar) : void
    {
        $this->stack->push($nullScalar->getValue());
    }

    public function visitListCollection(ListCollection $listCollection) : void
    {
        $list = [];

        foreach ($listCollection as $listItem) {
            $listItem->dispatch($this);

            $list[] = $this->stack->pop();
        }

        $this->stack->push($list);
    }

    public function visitMapCollection(MapCollection $mapCollection) : void
    {
        $map = [];

        foreach ($mapCollection as $mapItem) {
            $mapItem->dispatch($this);
            $map[$this->stack->pop()] = $this->stack->pop();
        }

        $this->stack->push($map);
    }

    public function visitConstantFetch(ConstantFetch $constantFetch) : void
    {
        $constantFetch->getName()->dispatch($this);
        $constantFetch->getClass()->dispatch($this);

        $this->stack->push(constant($this->stack->pop() . '::' . $this->stack->pop()));
    }

    /**
     * @param iterable<string, mixed> $parameters
     */
    private function construct(string $name, iterable $parameters) : object
    {
        // TODO refactor out
        if ((new ReflectionClass($name))->getConstructor() !== null) {
            return $this->constructorStrategy->construct($this->metadataCollection[$name], $parameters);
        }

        return $this->propertyStrategy->construct($this->metadataCollection[$name], $parameters);
    }
}
