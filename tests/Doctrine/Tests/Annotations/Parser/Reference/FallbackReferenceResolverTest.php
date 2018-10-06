<?php
declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Parser\Reference;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Reference\FallbackReferenceResolver;
use Doctrine\Tests\Annotations\Annotation\Parser\ScopeMother;
use PHPUnit\Framework\TestCase;

class FallbackReferenceResolverTest extends TestCase
{
    /** @var FallbackReferenceResolver */
    private $resolver;

    public function setUp()
    {
        $this->resolver = new FallbackReferenceResolver();
    }

    /**
     * @dataProvider fullyQualifiedIdentifiers
     */
    public function testResolvesIdentifiersMarkedAsFullyQualified(Reference $reference, string $expected)
    {
        $result = $this->resolver->resolve($reference, ScopeMother::example());

        $this->assertSame($expected, $result);
    }

    public function fullyQualifiedIdentifiers()
    {
        yield 'true FCQN' => [
            new Reference(self::class, true),
            self::class
        ];

        yield 'random string marked as FCQN' => [
            new Reference('foo', true),
            'foo'
        ];
    }
}
