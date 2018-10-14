<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata\Constraint;

use Doctrine\Annotations\Annotation;
use Doctrine\Annotations\Metadata\Constraint\ConstraintNotFulfilled;
use Doctrine\Annotations\Metadata\Constraint\TypeConstraint;
use Doctrine\Annotations\Metadata\Type\ObjectType;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Annotations\Metadata\Type\Type;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAll;
use PHPUnit\Framework\TestCase;

class TypeConstraintTest extends TestCase
{
    /**
     * @param mixed $value
     *
     * @dataProvider matchingExamples
     */
    public function testValidatesValueMatchingType(Type $type, $value) : void
    {
        $constraint = new TypeConstraint($type);

        $constraint->validate($value);

        $this->assertTrue(true);
    }

    /**
     * @return mixed[]
     */
    public function matchingExamples() : iterable
    {
        yield 'a string' => [
            new StringType(),
            'foo',
        ];

        yield 'an object' => [
            new ObjectType(Annotation::class),
            new Annotation([]),
        ];
    }

    /**
     * @param mixed $value
     *
     * @dataProvider notMatchingExamples
     */
    public function testValidatesValueNotMatchingTypeAndThrows(Type $type, $value) : void
    {
        $constraint = new TypeConstraint($type);

        $this->expectException(ConstraintNotFulfilled::class);

        $constraint->validate($value);
    }

    /**
     * @return mixed[]
     */
    public function notMatchingExamples() : iterable
    {
        yield 'not a string' => [
            new StringType(),
            42,
        ];

        yield 'a different object' => [
            new ObjectType(AnnotationTargetAll::class),
            new Annotation([]),
        ];
    }
}
