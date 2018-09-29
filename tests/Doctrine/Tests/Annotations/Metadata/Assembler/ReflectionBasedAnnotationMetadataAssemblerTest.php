<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata\Assembler;

use Doctrine\Annotations\Metadata\AnnotationTarget;
use Doctrine\Annotations\Metadata\Assembler\ReflectionBasedAnnotationMetadataAssembler;
use Doctrine\Annotations\Metadata\ScopeManufacturer;
use Doctrine\Annotations\Metadata\Type\MixedType;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Compiler;
use Doctrine\Annotations\Parser\Imports;
use Doctrine\Annotations\Parser\Reference\FallbackReferenceResolver;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Annotations\PhpParser;
use Doctrine\Annotations\TypeParser\PHPStanTypeParser;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAll;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser as PhpDocTypeParser;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class ReflectionBasedAnnotationMetadataAssemblerTest extends TestCase
{
    /** @var ReflectionBasedAnnotationMetadataAssembler */
    private $assembler;

    protected function setUp() : void
    {
        $this->assembler = new ReflectionBasedAnnotationMetadataAssembler(
            new Compiler(),
            new FallbackReferenceResolver(),
            new PHPStanTypeParser(new Lexer(), new PhpDocParser(new PhpDocTypeParser(), new ConstExprParser())),
            new ScopeManufacturer(new PhpParser())
        );
    }

    public function test() : void
    {
        $metadata = $this->assembler->assemble(
            new Reference(AnnotationTargetAll::class, true),
            new Scope(new ReflectionClass(AnnotationTargetAll::class), new Imports([]))
        );

        self::assertSame(AnnotationTargetAll::class, $metadata->getName());
        self::assertSame(AnnotationTarget::TARGET_ALL, $metadata->getTarget()->get());
        self::assertCount(3, $metadata->getProperties());
        self::assertSame('data', $metadata->getProperties()[0]->getName());
        self::assertInstanceOf(MixedType::class, $metadata->getProperties()[0]->getType());
        self::assertSame('name', $metadata->getProperties()[1]->getName());
        self::assertInstanceOf(MixedType::class, $metadata->getProperties()[1]->getType());
        self::assertSame('target', $metadata->getProperties()[2]->getName());
        self::assertInstanceOf(MixedType::class, $metadata->getProperties()[2]->getType());
    }
}
