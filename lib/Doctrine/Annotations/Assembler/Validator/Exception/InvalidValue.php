<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Validator\Exception;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use LogicException;
use function sprintf;

final class InvalidValue extends LogicException implements ValidationException
{
    public static function new(AnnotationMetadata $annotationMetadata, PropertyMetadata $propertyMetadata) : self
    {
        return new self(
            sprintf(
                'Invalid value for annotation property %s::%s.',
                $annotationMetadata->getName(),
                $propertyMetadata->getName()
            )
        );
    }
}
