<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Visitor;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\Assembler\AnnotationMetadataAssembler;
use Doctrine\Annotations\Parser\Ast\Annotation;
use Doctrine\Annotations\Parser\Ast\Annotations;
use Doctrine\Annotations\Parser\Ast\Collection\ListCollection;
use Doctrine\Annotations\Parser\Ast\Collection\MapCollection;
use Doctrine\Annotations\Parser\Ast\ConstantFetch;
use Doctrine\Annotations\Parser\Ast\Node;
use Doctrine\Annotations\Parser\Ast\Pair;
use Doctrine\Annotations\Parser\Ast\Parameter\NamedParameter;
use Doctrine\Annotations\Parser\Ast\Parameter\UnnamedParameter;
use Doctrine\Annotations\Parser\Scope;
use SplStack;

final class MetadataCollector implements Visitor
{
    /** @var AnnotationMetadataAssembler */
    private $metadataAssembler;

    /** @var Scope */
    private $scope;

    /** @var SplStack */
    private $stack;

    /** @var AnnotationMetadata[] */
    private $result = [];

    public function __construct(AnnotationMetadataAssembler $metadataAssembler, Scope $scope)
    {
        $this->metadataAssembler = $metadataAssembler;
        $this->scope             = $scope;
        $this->stack             = new SplStack();
    }

    /**
     * @return AnnotationMetadata[]
     */
    public function collect() : array
    {
        return $this->result;
    }

    public function visit(Node $node) : void
    {
        $node->dispatch($this);
    }

    public function visitAnnotations(Annotations $annotations) : void
    {
        foreach ($annotations as $annotation) {
            $annotation->dispatch($this);
        }
    }

    public function visitAnnotation(Annotation $annotation) : void
    {
        $annotation->getParameters()->dispatch($this);
        $annotation->getName()->dispatch($this);

        $this->result[] = $this->metadataAssembler->assemble(
            $this->stack->pop(),
            $this->scope
        );
    }

    public function visitReference(Reference $reference) : void
    {
        $this->stack->push($reference);
    }

    public function visitParameters(Parameters $parameters) : void
    {
        foreach ($parameters as $parameter) {
            $parameter->dispatch($this);
        }
    }

    public function visitNamedParameter(NamedParameter $parameter) : void
    {
        $parameter->getValue()->dispatch($this);
        $parameter->getName()->dispatch($this);
    }

    public function visitUnnamedParameter(UnnamedParameter $parameter) : void
    {
        $parameter->getValue()->dispatch($this);
    }

    public function visitListCollection(ListCollection $listCollection) : void
    {
        foreach ($listCollection as $item) {
            $item->dispatch($this);
        }
    }

    public function visitMapCollection(MapCollection $mapCollection) : void
    {
        foreach ($mapCollection as $item) {
            $item->dispatch($this);
        }
    }

    public function visitPair(Pair $pair) : void
    {
        $pair->getValue()->dispatch($this);
        $pair->getKey()->dispatch($this);
    }

    public function visitIdentifier(Identifier $identifier) : void
    {
    }

    public function visitConstantFetch(ConstantFetch $constantFetch) : void
    {
        $constantFetch->getName()->dispatch($this);
        $constantFetch->getClass()->dispatch($this);
    }

    public function visitNullScalar(NullScalar $nullScalar) : void
    {
    }

    public function visitBooleanScalar(BooleanScalar $booleanScalar) : void
    {
    }

    public function visitIntegerScalar(IntegerScalar $integerScalar) : void
    {
    }

    public function visitFloatScalar(FloatScalar $floatScalar) : void
    {
    }

    public function visitStringScalar(StringScalar $stringScalar) : void
    {
    }
}
