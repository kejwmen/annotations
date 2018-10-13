<?php
declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata\Constraint;

use Doctrine\Annotations\Metadata\Constraint\CompositeConstraint;
use Doctrine\Annotations\Metadata\Constraint\ConstraintNotFulfilled;
use Doctrine\Annotations\Metadata\Constraint\RequiredConstraint;
use Doctrine\Annotations\Metadata\Constraint\TypeConstraint;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Tests\Annotations\Metadata\Type\TestNullableType;
use PHPUnit\Framework\TestCase;

class CompositeConstraintTest extends TestCase
{
    public function testCombinesMultipleConstraintAndPassesWhenAllAreFulfilled()
    {
        $constraint = new CompositeConstraint(
            new TypeConstraint(TestNullableType::fromType(new StringType())),
            new RequiredConstraint()
        );

        $constraint->validate("foo");

        $this->expectException(ConstraintNotFulfilled::class);

        $constraint->validate(null);
    }
}
