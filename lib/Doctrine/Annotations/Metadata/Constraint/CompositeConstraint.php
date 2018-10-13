<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Constraint;

final class CompositeConstraint implements Constraint
{
    /** @var Constraint[] */
    private $constraints;

    public function __construct(Constraint ...$constraints)
    {
        $this->constraints = $constraints;
    }

    public function validate($value) : void
    {
        foreach ($this->constraints as $constraint) {
            $constraint->validate($value);
        }
    }
}
