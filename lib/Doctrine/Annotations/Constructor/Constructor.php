<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Constructor;

use Doctrine\Annotations\Constructor\Instantiator\Instantiator;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\InvalidAnnotationValue;
use Doctrine\Annotations\Metadata\InvalidPropertyValue;
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

        foreach ($parameters as $propertyName => $propertyValue) {
            if ($propertyName === '') {
                if ($annotationMetadata->hasConstructor()) {
                    continue;
                }

                $propertyName = $annotationMetadata->getDefaultProperty()->getName();
            }

            $propertyMetadata = $annotationMetadata->getProperties()[$propertyName];

            try {
                $propertyMetadata->validateValue($propertyValue);
            } catch (InvalidPropertyValue $exception) {
                throw InvalidAnnotationValue::new($annotationMetadata, $exception);
            }
        }

        return $this->instantiator->instantiate($annotationMetadata, $parameters);
    }
}
