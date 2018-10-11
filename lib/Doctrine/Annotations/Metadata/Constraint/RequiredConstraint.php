<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Constraint;

final class RequiredConstraint implements Constraint
{
    /**
     * @param mixed $value
     */
    public function validate($value) : bool
    {
        if ($value === null) {
            throw MissingRequiredValue::new();
        }

        return true;
    }
}
