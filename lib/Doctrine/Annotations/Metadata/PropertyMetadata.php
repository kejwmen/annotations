<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

use Doctrine\Annotations\Metadata\Constraint\Constraint;
use Doctrine\Annotations\Metadata\Constraint\ConstraintNotFulfilled;

final class PropertyMetadata
{
    /** @var string */
    private $name;

    /** @var Constraint */
    private $valueConstraint;

    public function __construct(string $name, Constraint $valueConstraint)
    {
        $this->name            = $name;
        $this->valueConstraint = $valueConstraint;
    }

    public function getName() : string
    {
        return $this->name;
    }

    /**
     * @param mixed $value
     *
     * @throws InvalidPropertyValue
     */
    public function validateValue($value) : void
    {
        try {
            $this->valueConstraint->validate($value);
        } catch (ConstraintNotFulfilled $exception) {
            throw InvalidPropertyValue::new($this, $exception);
        }
    }
}
