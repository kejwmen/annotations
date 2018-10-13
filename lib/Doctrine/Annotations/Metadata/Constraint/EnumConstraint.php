<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Constraint;

use function in_array;

final class EnumConstraint implements Constraint
{
    /** @var array */
    private $allowedValues;

    public function __construct(array $allowedValues)
    {
        $this->allowedValues = $allowedValues;
    }

    public function validate($value) : void
    {
        if (! in_array($value, $this->allowedValues)) {
            throw InvalidValue::new($this->allowedValues, $value);
        }
    }
}
