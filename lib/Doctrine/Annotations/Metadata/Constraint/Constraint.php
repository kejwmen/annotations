<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Constraint;

interface Constraint
{
    /**
     * @throws ConstraintNotFulfilled
     */
    public function validate($value) : void;
}
