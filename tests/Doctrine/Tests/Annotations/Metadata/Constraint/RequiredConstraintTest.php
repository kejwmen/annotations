<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata\Constraint;

use Doctrine\Annotations\Metadata\Constraint\ConstraintNotFulfilled;
use Doctrine\Annotations\Metadata\Constraint\RequiredConstraint;
use PHPUnit\Framework\TestCase;

class RequiredConstraintTest extends TestCase
{
    public function testValidatesNotNullValue() : void
    {
        $constraint = new RequiredConstraint();

        $constraint->validate('foo');

        $this->assertTrue(true);
    }

    public function testValidatesNullValueAndThrows() : void
    {
        $constraint = new RequiredConstraint();

        $this->expectException(ConstraintNotFulfilled::class);

        $constraint->validate(null);
    }
}
