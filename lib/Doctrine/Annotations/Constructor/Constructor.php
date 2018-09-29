<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Constructor;

use Doctrine\Annotations\Assembler\Validator\TargetValidator;
use Doctrine\Annotations\Assembler\Validator\ValueValidator;
use Doctrine\Annotations\Constructor\Instantiator\Instantiator;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
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
     * @param iterable<string, mixed> $parameters
     */
    public function construct(AnnotationMetadata $annotationMetadata, Scope $scope, iterable $parameters) : object
    {
        (new TargetValidator())->validate($annotationMetadata, $scope);

        foreach ($parameters as $propertyName => $propertyValue) {
            if ($propertyName === '') {
                if ($annotationMetadata->hasConstructor()) {
                    continue;
                }

                $propertyName = $annotationMetadata->getDefaultProperty()->getName();
            }

            (new ValueValidator())->validate(
                $annotationMetadata,
                $annotationMetadata->getProperties()[$propertyName],
                $propertyValue
            );
        }

        return $this->instantiator->instantiate($annotationMetadata, $parameters);
    }
}
