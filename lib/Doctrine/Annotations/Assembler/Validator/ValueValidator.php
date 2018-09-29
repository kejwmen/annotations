<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Validator;

use Doctrine\Annotations\Assembler\Validator\Exception\InvalidValue;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\PropertyMetadata;

final class ValueValidator
{
    /**
     * @param mixed $value
     *
     * @throws InvalidValue
     */
    public function validate(AnnotationMetadata $annotationMetadata, PropertyMetadata $propertyMetadata, $value) : void
    {
        if ($propertyMetadata->getType()->validate($value)) {
            return;
        }

        throw InvalidValue::new($annotationMetadata, $propertyMetadata);
    }
}
