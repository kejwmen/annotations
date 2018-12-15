<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Validator\Exception;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use LogicException;
use function sprintf;

final class InvalidTarget extends LogicException implements ValidationException
{
    public static function class(AnnotationMetadata $metadata) : self
    {
        return new self(
            sprintf(
                'Class target is not allowed for annotation %s',
                $metadata->getName()
            )
        );
    }
    public static function property(AnnotationMetadata $metadata) : self
    {
        return new self(
            sprintf(
                'Property target is not allowed for annotation %s',
                $metadata->getName()
            )
        );
    }
    public static function method(AnnotationMetadata $metadata) : self
    {
        return new self(
            sprintf(
                'Method target is not allowed for annotation %s',
                $metadata->getName()
            )
        );
    }

    public static function annotation(AnnotationMetadata $metadata) : self
    {
        return new self(
            sprintf(
                'Annotation target is not allowed for annotation %s',
                $metadata->getName()
            )
        );
    }
}
