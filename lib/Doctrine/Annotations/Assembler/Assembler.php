<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\MetadataCollection;
use Doctrine\Annotations\Parser\Ast\Annotation;
use Doctrine\Annotations\Parser\Ast\Parameter\NamedParameter;
use Doctrine\Annotations\Parser\Ast\Parameter\UnnamedParameter;
use Doctrine\Annotations\Parser\Ast\Parameters;
use Doctrine\Annotations\Parser\Reference\ReferenceResolver;
use Doctrine\Annotations\Parser\Scope;
use function assert;
use function count;
use function iterator_to_array;

final class Assembler
{
    /** @var MetadataCollection */
    private $metadataCollection;

    /** @var ReferenceResolver */
    private $referenceResolver;

    public function __construct(MetadataCollection $metadataCollection, ReferenceResolver $referenceResolver)
    {
        $this->metadataCollection = $metadataCollection;
        $this->referenceResolver  = $referenceResolver;
    }

    public function assemble(Annotation $annotation, Scope $scope) : object
    {
        $className = $this->referenceResolver->resolve($annotation->getName(), $scope);
        $metadata  = $this->metadataCollection[$className];

        $properties = $this->collectProperties($annotation->getParameters());

        if ($metadata->hasConstructor()) {
            return $this->createAnnotationWithConstructor($metadata, $properties);
        }

        return $this->createAnnotationWithoutConstructor($metadata, $properties);
    }

    /**
     * @return mixed[]
     */
    private function collectProperties(Parameters $parameters) : array
    {
        if (count($parameters) === 1) {
            $parameter = iterator_to_array($parameters)[0];
            if ($parameter instanceof UnnamedParameter) {
                return ['value' => []]; // $parameter->getValue();
            }
        }

        $parameterValues = [];

        // TODO multiple unnamed?

        foreach ($parameters as $parameter) {
            assert($parameter instanceof NamedParameter);

            $parameterValues[$parameter->getName()->getValue()] = []; //$parameter->getValue();
        }

        return $parameterValues;
    }

    /**
     * @param mixed[] $properties
     */
    private function createAnnotationWithConstructor(AnnotationMetadata $metadata, array $properties) : object
    {
        $class = $metadata->getName();
        return new $class($properties);
    }

    /**
     * @param mixed[] $properties
     */
    private function createAnnotationWithoutConstructor(AnnotationMetadata $metadata, array $properties) : object
    {
        $class      = $metadata->getName();
        $annotation = new $class();

        foreach ($properties as $name => $value) {
            $annotation->$name = $value;
        }

        return $annotation;
    }
}
