<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Constraint;

interface Constraint
{
    /**
     * @return true
     *
     * @throws ConstraintNotFulfilled
     */
    public function validate($value) : bool;
}
