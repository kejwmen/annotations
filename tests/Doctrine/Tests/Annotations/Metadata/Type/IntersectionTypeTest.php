<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata\Type;

use Doctrine\Annotations\Metadata\Type\IntersectionType;
use Doctrine\Annotations\Metadata\Type\ObjectType;
use Doctrine\Annotations\Metadata\Type\Type;
use IteratorAggregate;
use stdClass;

final class IntersectionTypeTest extends TypeTest
{
    protected function createType() : Type
    {
        return new IntersectionType(new ObjectType(stdClass::class), new ObjectType(IteratorAggregate::class));
    }

    public function testDescribe() : void
    {
        self::assertSame('stdClass&IteratorAggregate', $this->getType()->describe());
    }

    public function validValidateValuesProvider() : iterable
    {
        yield [
            new class extends stdClass implements IteratorAggregate {
                public function getIterator() : iterable
                {
                    yield 123;
                }
            },
        ];
    }

    public function invalidValidateValuesProvider() : iterable
    {
        yield [true];
        yield [0.0];
        yield ['0'];
        yield [[0]];
        yield [new stdClass()];
        yield [
            new class implements IteratorAggregate {
                public function getIterator() : iterable
                {
                    yield 123;
                }
            },
        ];
    }

    public function testAcceptsNull() : void
    {
        self::assertFalse($this->getType()->acceptsNull());
    }
}
