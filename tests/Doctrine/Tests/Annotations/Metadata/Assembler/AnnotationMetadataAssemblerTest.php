<?php
declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata\Assembler;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\Assembler\AnnotationMetadataAssembler;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use Doctrine\Annotations\Metadata\Type\MixedType;
use Doctrine\Annotations\Metadata\Type\ObjectType;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAnnotation;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithRequiredAttributes;
use PHPUnit\Framework\TestCase;
use Doctrine\Annotations\Assembler\Acceptor\InternalAcceptor;
use Doctrine\Annotations\Assembler\Assembler;
use Doctrine\Annotations\Constructor\Constructor;
use Doctrine\Annotations\Constructor\Instantiator\ConstructorInstantiatorStrategy;
use Doctrine\Annotations\Constructor\Instantiator\Instantiator;
use Doctrine\Annotations\Constructor\Instantiator\PropertyInstantiatorStrategy;
use Doctrine\Annotations\Metadata\InternalAnnotations;
use Doctrine\Annotations\Metadata\Reflection\DefaultReflectionProvider;
use Doctrine\Annotations\Metadata\ScopeManufacturer;
use Doctrine\Annotations\Parser\Compiler;
use Doctrine\Annotations\Parser\IgnoredAnnotations;
use Doctrine\Annotations\Parser\Imports;
use Doctrine\Annotations\Parser\Reference\FallbackReferenceResolver;
use Doctrine\Annotations\Parser\Reference\StaticReferenceResolver;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Annotations\PhpParser;
use Doctrine\Annotations\TypeParser\PHPStanTypeParser;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAll;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use ReflectionClass;

class AnnotationMetadataAssemblerTest extends TestCase
{
    /** @var Compiler */
    private $compiler;

    /** @var PhpParser */
    private $phpParser;

    /** @var AnnotationMetadataAssembler */
    private $metadataAssembler;

    /** @var Constructor */
    private $constructor;

    public function setUp()
    {
        $this->compiler = new Compiler();
        $this->phpParser = new PhpParser();
        $this->constructor = new Constructor(
            new Instantiator(
                new ConstructorInstantiatorStrategy(),
                new PropertyInstantiatorStrategy()
            )
        );
        $this->metadataAssembler = new AnnotationMetadataAssembler(
            $this->compiler,
            new FallbackReferenceResolver(),
            new DefaultReflectionProvider(),
            new PHPStanTypeParser(new Lexer(), new PhpDocParser(new \PHPStan\PhpDocParser\Parser\TypeParser(), new ConstExprParser()), new FallbackReferenceResolver()),
            new ScopeManufacturer($this->phpParser),
            new Assembler(
                InternalAnnotations::createMetadata(),
                new StaticReferenceResolver(),
                $this->constructor,
                new DefaultReflectionProvider(),
                new InternalAcceptor(
                    new StaticReferenceResolver()
                )
            )
        );
    }

    /**
     * @dataProvider validExamples
     */
    public function testAssemblingValidExamples(Reference $reference, Scope $scope, callable $asserter)
    {
        $metadata = $this->metadataAssembler->assemble($reference, $scope);

        $asserter($metadata);
    }

    public function validExamples(): iterable
    {
        yield 'fixture - AnnotationTargetAll' => [
            new Reference(AnnotationTargetAll::class, true),
            new Scope(new ReflectionClass($this), new Imports([]), new IgnoredAnnotations()),
            function (AnnotationMetadata $metadata) {
                $this->assertSame(AnnotationTargetAll::class, $metadata->getName());
                $this->assertTrue($metadata->getTarget()->all(), 'Invalid target');
                $this->assertFalse($metadata->hasConstructor(), 'Has constructor');
                $properties = $metadata->getProperties();
                $this->assertEquals(
                    [
                        'data' => new PropertyMetadata('data', new MixedType(), true),
                        'name' => new PropertyMetadata('name', new MixedType()),
                        'target' => new PropertyMetadata('target', new MixedType())
                    ],
                    $properties
                );
                $this->assertEquals(new PropertyMetadata('data', new MixedType(), true), $metadata->getDefaultProperty());
            }
        ];

        yield 'fixture - AnnotationWithRequiredAttributes' => [
            new Reference(AnnotationWithRequiredAttributes::class, true),
            new Scope(new ReflectionClass($this), new Imports([]), new IgnoredAnnotations()),
            function (AnnotationMetadata $metadata) {
                $this->assertSame(AnnotationWithRequiredAttributes::class, $metadata->getName());
                $this->assertTrue($metadata->getTarget()->all(), 'Invalid target');
                $this->assertTrue($metadata->hasConstructor(), 'Has no constructor');
                $properties = $metadata->getProperties();
                $this->assertEmpty($properties);
                $this->assertNull($metadata->getDefaultProperty());
            }
        ];
    }
}
