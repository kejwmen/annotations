<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata\Type;

use Doctrine\Annotations\Metadata\Type\ListType;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Annotations\Metadata\Type\Type;

final class ListTypeTest extends TypeTest
{
    protected function createType(): Type
    {
        return new ListType($this->getInternalType());
    }

    public function testDescribe(): void
    {
        self::assertSame(sprintf('array<%s>', $this->getInternalType()->describe()), $this->getType()->describe());
    }

    public function validValidateValuesProvider(): iterable
    {
        yield [
            ['foo', 'bar']
        ];
    }

    public function invalidValidateValuesProvider(): iterable
    {
        yield [
            ['foo', 1],
            ['foo' => 'bar'],
            [new \stdClass()]
        ];
    }

    public function testAcceptsNull(): void
    {
        self::assertSame($this->getInternalType()->acceptsNull(), $this->getType()->acceptsNull());
    }

    private function getInternalType(): Type
    {
        return new StringType();
    }
}
