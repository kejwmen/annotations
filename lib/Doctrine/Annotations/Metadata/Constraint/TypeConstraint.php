<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Constraint;

use Doctrine\Annotations\Metadata\Type\Type;

final class TypeConstraint implements Constraint
{
    /** @var Type */
    private $type;

    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    public function validate($value) : void
    {
        if (! $this->type->validate($value)) {
            throw InvalidType::new($this->type->describe(), $value);
        }
    }
}
