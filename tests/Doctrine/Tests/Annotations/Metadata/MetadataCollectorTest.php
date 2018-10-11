<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationTarget;
use Doctrine\Annotations\Metadata\Assembler\AnnotationMetadataAssembler;
use Doctrine\Annotations\Metadata\MetadataCollection;
use Doctrine\Annotations\Metadata\MetadataCollector;
use Doctrine\Annotations\Parser\Ast\Annotation;
use Doctrine\Annotations\Parser\Ast\Annotations;
use Doctrine\Annotations\Parser\Ast\Parameter\UnnamedParameter;
use Doctrine\Annotations\Parser\Ast\Parameters;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Reference\StaticReferenceResolver;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Tests\Annotations\Annotation\Parser\ScopeMother;
use Doctrine\Tests\Annotations\Assembler\Acceptor\AlwaysAcceptingAcceptor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class MetadataCollectorTest extends TestCase
{
    /** @var AnnotationMetadataAssembler|MockObject */
    private $assembler;

    /** @var MetadataCollector */
    private $collector;

    protected function setUp() : void
    {
        $this->markTestIncomplete('Collector has incorrect setup');

        $this->assembler = $this->createMock(AnnotationMetadataAssembler::class);
        $this->collector = new MetadataCollector(
            $this->assembler,
            new AlwaysAcceptingAcceptor(),
            new StaticReferenceResolver()
        );
    }

    /**
     * @param callable(AnnotationMetadataAssembler) : void $initializer
     * @param callable(AnnotationMetadata[]) : void        $asserter
     *
     * @dataProvider docBlocksProvider()
     */
    public function testMetadata(Annotations $annotations, callable $initializer, callable $asserter) : void
    {
        $initializer($this->assembler);

        $collection = new MetadataCollection();

        $this->collector->collect($annotations, ScopeMother::example(), $collection);

        $asserter(...$collection);
    }

    public function docBlocksProvider() : iterable
    {
        yield 'single without parameters' => [
            new Annotations(
                new Annotation(
                    new Reference('Foo', true),
                    new Parameters()
                )
            ),
            function (AnnotationMetadataAssembler $assembler) : void {
                $assembler->method('assemble')
                    ->with(
                        $this->callback(static function (Reference $reference) : bool {
                            return $reference->getIdentifier() === 'Foo' && $reference->isFullyQualified() === true;
                        }),
                        $this->isInstanceOf(Scope::class)
                    )
                    ->willReturn(new AnnotationMetadata(
                        'Foo',
                        new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                        false,
                        []
                    ));
            },
            static function (AnnotationMetadata ...$metadatas) : void {
                self::assertCount(1, $metadatas);
                self::assertSame('Foo', $metadatas[0]->getName());
            },
        ];
        yield 'nested' => [
            new Annotations(
                new Annotation(
                    new Reference('Foo', true),
                    new Parameters(
                        new UnnamedParameter(
                            new Annotation(
                                new Reference('Bar', false),
                                new Parameters()
                            )
                        )
                    )
                )
            ),
            function (AnnotationMetadataAssembler $assembler) : void {
                $assembler->method('assemble')
                    ->withConsecutive(
                        [
                            $this->callback(static function (Reference $reference) : bool {
                                return $reference->getIdentifier() === 'Bar' && $reference->isFullyQualified() === false;
                            }),
                            $this->isInstanceOf(Scope::class),
                        ],
                        [
                            $this->callback(static function (Reference $reference) : bool {
                                return $reference->getIdentifier() === 'Foo' && $reference->isFullyQualified() === true;
                            }),
                            $this->isInstanceOf(Scope::class),
                        ]
                    )
                    ->willReturnOnConsecutiveCalls(
                        new AnnotationMetadata(
                            'Bar',
                            new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                            false,
                            []
                        ),
                        new AnnotationMetadata(
                            'Foo',
                            new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                            false,
                            []
                        )
                    );
            },
            static function (AnnotationMetadata ...$metadatas) : void {
                self::assertCount(2, $metadatas);
                self::assertSame('Bar', $metadatas[0]->getName());
                self::assertSame('Foo', $metadatas[1]->getName());
            },
        ];
    }
}
