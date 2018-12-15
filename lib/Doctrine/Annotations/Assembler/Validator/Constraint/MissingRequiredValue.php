<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Validator\Constraint;

final class MissingRequiredValue extends ConstraintNotFulfilled
{
    public static function new() : self
    {
        return new self('Required value is null');
    }
}
