<?php
declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Parser\Visitor;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationTarget;
use Doctrine\Annotations\Metadata\Assembler\AnnotationMetadataAssembler;
use Doctrine\Annotations\Parser\Ast\Annotation;
use Doctrine\Annotations\Parser\Ast\Annotations;
use Doctrine\Annotations\Parser\Ast\Parameter\UnnamedParameter;
use Doctrine\Annotations\Parser\Ast\Parameters;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Imports;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Annotations\Metadata\MetadataCollector;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class MetadataBuilderTest extends TestCase
{
    /** @var AnnotationMetadataAssembler|MockObject */
    private $assembler;

    /** @var MetadataCollector */
    private $builder;

    protected function setUp() : void
    {
        $this->assembler = $this->createMock(AnnotationMetadataAssembler::class);
        $this->builder   = new MetadataCollector(
            $this->assembler,
            new Scope(
                $this->createMock(ReflectionClass::class),
                new Imports([])
            )
        );
    }

    /**
     * @param callable(AnnotationMetadataAssembler) : void $initializer
     * @param callable(AnnotationMetadata[]) : void $asserter
     *
     * @dataProvider docBlocksProvider()
     */
    public function testMetadata(Annotations $annotations, callable $initializer, callable $asserter) : void
    {
        $initializer($this->assembler);

        $this->builder->visit($annotations);

        $asserter(...$this->builder->collect());
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
                        $this->callback(function (Reference $reference) : bool {
                            return $reference->getIdentifier() === 'Foo' && $reference->isFullyQualified() === true;
                        }),
                        $this->isInstanceOf(Scope::class)
                    )
                    ->willReturn(new AnnotationMetadata(
                        'Foo',
                        new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                        false,
                        [],
                        null
                    ));
            },
            function (AnnotationMetadata ...$metadatas) : void {
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
                            $this->callback(function (Reference $reference) : bool {
                                return $reference->getIdentifier() === 'Bar' && $reference->isFullyQualified() === false;
                            }),
                            $this->isInstanceOf(Scope::class)
                        ],
                        [
                            $this->callback(function (Reference $reference) : bool {
                                return $reference->getIdentifier() === 'Foo' && $reference->isFullyQualified() === true;
                            }),
                            $this->isInstanceOf(Scope::class)
                        ]
                    )
                    ->willReturnOnConsecutiveCalls(
                        new AnnotationMetadata(
                            'Bar',
                            new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                            false,
                            [],
                            null
                        ),
                        new AnnotationMetadata(
                            'Foo',
                            new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                            false,
                            [],
                            null
                        )
                    );
            },
            function (AnnotationMetadata ...$metadatas) : void {
                self::assertCount(2, $metadatas);
                self::assertSame('Bar', $metadatas[0]->getName());
                self::assertSame('Foo', $metadatas[1]->getName());
            },
        ];
    }
}
