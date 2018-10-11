<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Constraint;

interface Constraint
{
    /**
     * @param mixed $value
     *
     * @return true
     *
     * @throws ConstraintNotFulfilled
     */
    public function validate($value) : bool;
}
