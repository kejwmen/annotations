<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Assembler\Constant;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Annotations\Assembler\Constant\Exception\ClassConstantNotFound;
use Doctrine\Annotations\Assembler\Constant\Exception\ConstantNotAccessible;
use Doctrine\Annotations\Assembler\Constant\Exception\InvalidClass;
use Doctrine\Annotations\Assembler\Constant\ReflectionConstantResolver;
use Doctrine\Annotations\Metadata\Reflection\DefaultReflectionProvider;
use PHPUnit\Framework\TestCase;
use const PHP_EOL;
use const PHP_VERSION_ID;
use function sprintf;

final class ReflectionConstantResolverTest extends TestCase
{
    /** @var ReflectionConstantResolver */
    private $resolver;

    protected function setUp() : void
    {
        $this->resolver = new ReflectionConstantResolver(new DefaultReflectionProvider());
    }

    /**
     * @param mixed $value
     *
     * @dataProvider validStandaloneConstantsProvider()
     */
    public function testValidStandaloneConstant(string $constantName, $value) : void
    {
        self::assertSame($value, $this->resolver->resolveStandaloneConstant($constantName));
    }

    /**
     * @return mixed[][]
     */
    public function validStandaloneConstantsProvider() : iterable
    {
        yield ['PHP_EOL', PHP_EOL];
        yield ['PHP_VERSION_ID', PHP_VERSION_ID];
        yield [__NAMESPACE__ . '\SOME_CONSTANT', SOME_CONSTANT];
    }

    /**
     * @param mixed $value
     *
     * @dataProvider validClassConstantsProvider()
     */
    public function testValidClassConstant(string $className, string $constantName, $value) : void
    {
        self::assertSame($value, $this->resolver->resolveClassOrInterfaceConstant($className, $constantName));
    }

    /**
     * @return mixed[][]
     */
    public function validClassConstantsProvider() : iterable
    {
        yield 'class' => [DateTimeImmutable::class, 'RFC3339', DateTimeImmutable::RFC3339];
        yield 'interface' => [DateTimeInterface::class, 'RFC3339', DateTimeInterface::RFC3339];
        yield 'user-defined' => [SomeClass::class, 'PUBLIC_CONSTANT', SomeClass::PUBLIC_CONSTANT];
    }

    public function testInvalidClass() : void
    {
        $this->expectException(InvalidClass::class);

        $this->resolver->resolveClassOrInterfaceConstant(__NAMESPACE__ . '\InvalidClass', 'INVALID_CONSTANT');
    }

    public function testNonexistentClassConstant() : void
    {
        $this->expectException(ClassConstantNotFound::class);
        $this->expectExceptionMessage(
            sprintf(
                'Class or interface constant %s::INVALID_CONSTANT does not exist.',
                SomeClass::class
            )
        );

        $this->resolver->resolveClassOrInterfaceConstant(SomeClass::class, 'INVALID_CONSTANT');
    }

    public function testPrivateClassConstant() : void
    {
        $this->expectException(ConstantNotAccessible::class);
        $this->expectExceptionMessage(
            sprintf(
                "Could not access constant %s::PRIVATE_CONSTANT because it's not public.",
                SomeClass::class
            )
        );

        $this->resolver->resolveClassOrInterfaceConstant(SomeClass::class, 'PRIVATE_CONSTANT');
    }
}

const SOME_CONSTANT = 123;

final class SomeClass
{
    public const PUBLIC_CONSTANT   = 123;
    private const PRIVATE_CONSTANT = 456;
}
