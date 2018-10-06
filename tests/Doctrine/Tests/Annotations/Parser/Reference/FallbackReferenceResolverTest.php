<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Parser\Reference;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Reference\FallbackReferenceResolver;
use Doctrine\Tests\Annotations\Parser\ScopeMother;
use PHPUnit\Framework\TestCase;

class FallbackReferenceResolverTest extends TestCase
{
    /** @var FallbackReferenceResolver */
    private $resolver;

    public function setUp() : void
    {
        $this->resolver = new FallbackReferenceResolver();
    }

    /**
     * @dataProvider fullyQualifiedIdentifiers
     */
    public function testResolvesIdentifiersMarkedAsFullyQualified(Reference $reference, string $expected) : void
    {
        $result = $this->resolver->resolve($reference, ScopeMother::example());

        $this->assertSame($expected, $result);
    }

    /**
     * @return mixed[]
     */
    public function fullyQualifiedIdentifiers() : iterable
    {
        yield 'true FCQN' => [
            new Reference(self::class, true),
            self::class,
        ];

        yield 'random string marked as FCQN' => [
            new Reference('foo', true),
            'foo',
        ];
    }
}
