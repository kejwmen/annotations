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

    /**
     * @param mixed $value
     */
    public function validate($value) : bool
    {
        foreach ($this->constraints as $constraint) {
            $constraint->validate($value);
        }

        return true;
    }
}
