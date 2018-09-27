<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler;

use Doctrine\Annotations\Metadata\MetadataCollection;
use Doctrine\Annotations\Parser\Ast\Annotation;
use Doctrine\Annotations\Parser\Ast\Parameter\NamedParameter;
use Doctrine\Annotations\Parser\Ast\Parameter\UnnamedParameter;
use Doctrine\Annotations\Parser\Ast\Parameters;
use Doctrine\Annotations\Parser\Reference\ReferenceResolver;
use Doctrine\Annotations\Parser\Scope;
use function count;
use function iterator_to_array;

final class Assembler
{
    /** @var MetadataCollection */
    private $metadataCollection;

    /** @var ReferenceResolver */
    private $referenceResolver;

    /** @var ConstructorStrategy */
    private $constructorStrategy;

    /** @var PropertyStrategy */
    private $propertyStrategy;

    public function __construct(
        MetadataCollection $metadataCollection,
        ReferenceResolver $referenceResolver,
        ConstructorStrategy $constructorStrategy,
        PropertyStrategy $propertyStrategy
    ) {
        $this->metadataCollection  = $metadataCollection;
        $this->referenceResolver   = $referenceResolver;
        $this->constructorStrategy = $constructorStrategy;
        $this->propertyStrategy    = $propertyStrategy;
    }

    public function assemble(Annotation $annotation, Scope $scope) : object
    {
        $className = $this->referenceResolver->resolve($annotation->getName(), $scope);
        $metadata  = $this->metadataCollection[$className];

        $properties = $this->collectProperties($annotation->getParameters());

        if ($metadata->hasConstructor()) {
            return $this->constructorStrategy->construct($metadata, $properties);
        }

        return $this->propertyStrategy->construct($metadata, $properties);
    }

    /**
     * @return mixed[]
     */
    private function collectProperties(Parameters $parameters) : array
    {
        // TODO validate properties

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
}
