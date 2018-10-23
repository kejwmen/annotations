<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Parser\Reference;

use Doctrine\Annotations\Annotation\Required;
use Doctrine\Annotations\Annotation\Target;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Reference\Exception\ReferenceNotResolvable;
use Doctrine\Annotations\Parser\Reference\StaticReferenceResolver;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Tests\Annotations\Parser\ScopeMother;
use PHPUnit\Framework\TestCase;

class StaticReferenceResolverTest extends TestCase
{
    /** @var StaticReferenceResolver */
    private $resolver;

    public function setUp() : void
    {
        $this->resolver = new StaticReferenceResolver();
    }

    /**
     * @dataProvider resolvableExamples
     */
    public function testResolvesResolvableExamples(Reference $reference, Scope $scope, string $expected) : void
    {
        $result = $this->resolver->resolve($reference, $scope);

        $this->assertSame($expected, $result);
    }

    /**
     * @return mixed[]
     */
    public function resolvableExamples() : iterable
    {
        yield 'FCQN' => [
            new Reference(self::class, true),
            ScopeMother::withImports([
                'this' => self::class,
            ]),
            self::class,
        ];

        yield 'aliased' => [
            new Reference('foo', false),
            ScopeMother::withImports([
                'foo' => Target::class,
            ]),
            Target::class,
        ];
    }

    /**
     * @dataProvider notResolvableExamples
     */
    public function testResolvesNotResolvableExamplesAndThrows(Reference $reference, Scope $scope) : void
    {
        $this->expectException(ReferenceNotResolvable::class);

        $this->resolver->resolve($reference, $scope);
    }

    /**
     * @return mixed[]
     */
    public function notResolvableExamples() : iterable
    {
        yield 'unknown FQCN' => [
            new Reference(Target::class, true),
            ScopeMother::withImports([
                'that' => Required::class,
            ]),
        ];

        yield 'without alias' => [
            new Reference('foo', false),
            ScopeMother::withImports([
                'bar' => Target::class,
            ]),
        ];
    }
}
