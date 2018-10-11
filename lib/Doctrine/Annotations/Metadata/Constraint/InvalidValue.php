<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Constraint;

use function gettype;
use function implode;
use function sprintf;

final class InvalidValue extends ConstraintNotFulfilled
{
    public static function new(array $allowedValues, $value)
    {
        // TODO: Describe values
        return new self(
            sprintf(
                'Invalid value "%s" for allowed values: "%s".',
                gettype($value),
                implode(',', $allowedValues)
            )
        );
    }
}
