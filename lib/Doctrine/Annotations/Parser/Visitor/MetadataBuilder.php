<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Visitor;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\Parameter\NamedAnnotationParameter;
use Doctrine\Annotations\Metadata\Parameter\UnnamedAnnotationParameter;
use Doctrine\Annotations\Metadata\ReflectionMetadataAssembler;
use Doctrine\Annotations\Parser\Ast\Annotation;
use Doctrine\Annotations\Parser\Ast\Annotations;
use Doctrine\Annotations\Parser\Ast\Collection\ListCollection;
use Doctrine\Annotations\Parser\Ast\Collection\MapCollection;
use Doctrine\Annotations\Parser\Ast\ConstantFetch;
use Doctrine\Annotations\Parser\Ast\Parameter\NamedParameter;
use Doctrine\Annotations\Parser\Ast\Node;
use Doctrine\Annotations\Parser\Ast\Pair;
use Doctrine\Annotations\Parser\Ast\Parameters;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Ast\Scalar\BooleanScalar;
use Doctrine\Annotations\Parser\Ast\Scalar\FloatScalar;
use Doctrine\Annotations\Parser\Ast\Scalar\Identifier;
use Doctrine\Annotations\Parser\Ast\Scalar\IntegerScalar;
use Doctrine\Annotations\Parser\Ast\Scalar\NullScalar;
use Doctrine\Annotations\Parser\Ast\Scalar\StringScalar;
use Doctrine\Annotations\Parser\Ast\Parameter\UnnamedParameter;

final class MetadataBuilder implements Visitor
{
    /**
     * @var AnnotationMetadata[]
     */
    private $result = [];

    /**
     * @var \SplStack
     */
    private $stack;

    /**
     * @var ReflectionMetadataAssembler
     */
    private $metadataAssembler;

    public function __construct(ReflectionMetadataAssembler $metadataAssembler)
    {
        $this->metadataAssembler = $metadataAssembler;
        $this->stack = new \SplStack();
    }

    public function result(): array
    {
        return $this->result;
    }

    public function visit(Node $node) : void
    {
        $this->result = [];

        $node->dispatch($this);
    }

    public function visitAnnotations(Annotations $annotations) : void
    {
        foreach ($annotations as $annotation) {
            $annotation->dispatch($this);
            $this->result[] = $this->stack->pop();
        }
    }

    public function visitAnnotation(Annotation $annotation) : void
    {
        $annotation->getParameters()->dispatch($this);
        $annotation->getName()->dispatch($this);

        $this->stack->push($this->metadataAssembler->get(
            $this->stack->pop(),
            $this->stack->pop()
        ));
    }

    public function visitReference(Reference $reference) : void
    {
        $this->stack->push($reference);
    }

    public function visitParameters(Parameters $parameters) : void
    {
        $currentParameter = [];

        foreach ($parameters as $parameter) {
            $parameter->dispatch($this);

            $currentParameter[] = $this->stack->pop();
        }

        $this->stack->push($currentParameter);
    }

    public function visitNamedParameter(NamedParameter $parameter) : void
    {
        $parameter->getValue()->dispatch($this);
        $parameter->getName()->dispatch($this);

        $this->stack->push(new NamedAnnotationParameter(
            $this->stack->pop(),
            $this->stack->pop()
        ));
    }

    public function visitUnnamedParameter(UnnamedParameter $parameter) : void
    {
        $parameter->getValue()->dispatch($this);

        $this->stack->push(new UnnamedAnnotationParameter(
            $this->stack->pop()
        ));
    }

    public function visitListCollection(ListCollection $listCollection) : void
    {
        $localList = [];
        foreach ($listCollection as $item) {
            $item->dispatch($this);
            $localList[] = $this->stack->pop();
        }

        $this->stack->push($localList);
    }

    public function visitMapCollection(MapCollection $mapCollection) : void
    {
        $localMap = [];

        foreach ($mapCollection as $item) {
            $item->dispatch($this);
            $localMap[$this->stack->pop()] = $this->stack->pop();
        }

        $this->stack->push($localMap);
    }

    public function visitPair(Pair $pair) : void
    {
        $pair->getValue()->dispatch($this);
        $pair->getKey()->dispatch($this);
    }

    public function visitIdentifier(Identifier $identifier) : void
    {
        $this->stack->push($identifier->getValue());
    }

    public function visitConstantFetch(ConstantFetch $constantFetch) : void
    {
        $constantFetch->getName()->dispatch($this);
        $constantFetch->getClass()->dispatch($this);
    }

    public function visitNullScalar(NullScalar $nullScalar) : void
    {
        $this->stack->push($nullScalar->getValue());
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
}
