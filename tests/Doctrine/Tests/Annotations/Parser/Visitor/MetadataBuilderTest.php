<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Parser\Visitor;

use Doctrine\Annotations\Annotation;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationProperty;
use Doctrine\Annotations\Metadata\ReflectionMetadataAssembler;
use Doctrine\Annotations\Parser\Visitor\MetadataBuilder;
use Doctrine\Annotations\Parser\Visitor\Raw\AstBuilder;
use Hoa\Compiler\Llk\Llk;
use Hoa\File\Read;
use PHPUnit\Framework\TestCase;

class MetadataBuilderTest extends TestCase
{
    /**
     * @param AnnotationMetadata[] $expected
     *
     * @dataProvider docBlocksProvider()
     */
    public function testMetadata(string $docBlock, array $expected) : void
    {
        $builder  = new MetadataBuilder(
            new ReflectionMetadataAssembler(
                ['Annotation' => Annotation::class]
            )
        );
        $compiler = Llk::load(new Read(__DIR__ . '/../../../../../../lib/Doctrine/Annotations/Parser/grammar.pp'));

        $hoaAst = $compiler->parse($docBlock);
        $ast    = (new AstBuilder())->visit($hoaAst);
        $builder->visit($ast);

        $metadata = $builder->result();

        self::assertEquals($expected, $metadata);
    }

    /**
     * @return string[][]|AnnotationMetadata[][][]
     */
    public function docBlocksProvider() : iterable
    {
        yield 'simple with no parenthesis' => [
            <<<'DOCBLOCK'
/**
* @Annotation
*/
DOCBLOCK
        ,
            [
                new AnnotationMetadata(
                    false,
                    null,
                    [],
                    [new AnnotationProperty('value')],
                    [],
                    []
                ),
            ],
        ];
    }
}
