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

    /** @var bool */
    private $default;

    public function __construct(string $name, Constraint $valueConstraint, bool $default = false)
    {
        $this->name            = $name;
        $this->valueConstraint = $valueConstraint;
        $this->default         = $default;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function isDefault() : bool
    {
        return $this->default;
    }

    /**
     * @param mixed $value
     *
     * @return true
     *
     * @throws InvalidPropertyValue
     */
    public function validateValue($value) : bool
    {
        try {
            $this->valueConstraint->validate($value);
        } catch (ConstraintNotFulfilled $exception) {
            throw InvalidPropertyValue::new($this, $exception);
        }

        return true;
    }
}
