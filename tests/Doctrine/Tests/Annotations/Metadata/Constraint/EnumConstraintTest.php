<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata\Constraint;

use Doctrine\Annotations\Metadata\Constraint\EnumConstraint;
use Doctrine\Annotations\Metadata\Constraint\InvalidValue;
use PHPUnit\Framework\TestCase;

class EnumConstraintTest extends TestCase
{
    /**
     * @dataProvider fulfilledExamples
     */
    public function testFulfilledByGivenValue(array $allowedValues, $value) : void
    {
        $constraint = new EnumConstraint($allowedValues);

        $this->assertTrue($constraint->validate($value));
    }

    public function fulfilledExamples() : iterable
    {
        yield 'matching string' => [
            ['foo', 'bar'],
            'bar',
        ];
    }

    /**
     * @dataProvider notFulfilledExamples
     */
    public function testNotFulfilledByGivenValue(array $allowedValues, $value) : void
    {
        $constraint = new EnumConstraint($allowedValues);

        $this->expectException(InvalidValue::class);

        $constraint->validate($value);
    }

    public function notFulfilledExamples() : iterable
    {
        yield 'not matching string' => [
            ['foo', 'bar'],
            'baz',
        ];
    }
}
