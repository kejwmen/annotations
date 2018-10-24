<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Constructor;

use Doctrine\Annotations\Constructor\Instantiator\Instantiator;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\InvalidAnnotationValue;
use Doctrine\Annotations\Metadata\InvalidPropertyValue;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use Doctrine\Annotations\Parser\Scope;

final class Constructor
{
    /** @var Instantiator */
    private $instantiator;

    public function __construct(Instantiator $instantiator)
    {
        $this->instantiator = $instantiator;
    }

    /**
     * @param mixed[] $parameters iterable<string, mixed>
     */
    public function construct(AnnotationMetadata $annotationMetadata, Scope $scope, iterable $parameters) : object
    {
        $annotationMetadata->validateTarget($scope);

        if (! $annotationMetadata->hasConstructor()) {
            $this->validateProperties($annotationMetadata, $parameters);
        }

        return $this->instantiator->instantiate($annotationMetadata, $parameters);
    }

    /**
     * @param mixed[] $parameters
     */
    private function validateProperties(AnnotationMetadata $annotationMetadata, iterable $parameters) : void
    {
        foreach ($parameters as $propertyName => $propertyValue) {
            $propertyMetadata = $this->getPropertyMetadata($annotationMetadata, $propertyName);
            try {
                $propertyMetadata->validateValue($propertyValue);
            } catch (InvalidPropertyValue $exception) {
                throw InvalidAnnotationValue::new($annotationMetadata, $exception);
            }
        }
    }

    private function getPropertyMetadata(AnnotationMetadata $annotationMetadata, string $propertyName) : PropertyMetadata
    {
        if ($propertyName === '') {
            /** @var PropertyMetadata $defaultProperty */
            $defaultProperty = $annotationMetadata->getDefaultProperty();

            $propertyName = $defaultProperty->getName();
        }

        return $annotationMetadata->getProperties()[$propertyName];
    }
}
