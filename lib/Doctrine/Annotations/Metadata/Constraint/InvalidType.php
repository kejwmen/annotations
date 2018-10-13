<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Constraint;

use function sprintf;

final class InvalidType extends ConstraintNotFulfilled
{
    public static function new(string $typeDescription, $value)
    {
        // TODO: Describe value
        return new self(
            sprintf(
                'Invalid value "%s" fo type %s.',
                !is_scalar($value) ? gettype($value) : $value,
                $typeDescription
            )
        );
    }
}
