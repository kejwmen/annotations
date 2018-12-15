<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Validator\Constraint;

interface Constraint
{
    /**
     * @param mixed $value
     *
     * @throws ConstraintNotFulfilled
     */
    public function validate($value) : void;
}
