<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata\Assembler;

use Doctrine\Annotations\Constructor\Constructor;
use Doctrine\Annotations\Constructor\Instantiator\ConstructorInstantiatorStrategy;
use Doctrine\Annotations\Constructor\Instantiator\Instantiator;
use Doctrine\Annotations\Constructor\Instantiator\PropertyInstantiatorStrategy;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\Assembler\AnnotationMetadataAssembler;
use Doctrine\Annotations\Metadata\Assembler\AnnotationMetadataAssemblerFactory;
use Doctrine\Annotations\Metadata\Constraint\CompositeConstraint;
use Doctrine\Annotations\Metadata\Constraint\RequiredConstraint;
use Doctrine\Annotations\Metadata\Constraint\TypeConstraint;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use Doctrine\Annotations\Metadata\Type\ObjectType;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Compiler;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Annotations\PhpParser;
use Doctrine\Tests\Annotations\Annotation\Parser\ScopeMother;
use Doctrine\Tests\Annotations\Fixtures\AnnotationEnum;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAll;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAnnotation;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithConstants;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithRequiredAttributes;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithRequiredAttributesWithoutConstructor;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithVarType;
use Doctrine\Tests\Annotations\Fixtures\Metadata\AnnotationEnumMetadata;
use Doctrine\Tests\Annotations\Fixtures\Metadata\AnnotationTargetAllMetadata;
use Doctrine\Tests\Annotations\Fixtures\Metadata\AnnotationWithConstantsMetadata;
use Doctrine\Tests\Annotations\Fixtures\Metadata\AnnotationWithRequiredAttributesMetadata;
use Doctrine\Tests\Annotations\Fixtures\Metadata\AnnotationWithVarTypeMetadata;
use Doctrine\Tests\Annotations\Metadata\Type\TestNullableType;
use PHPUnit\Framework\TestCase;
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

    public function setUp() : void
    {
        $this->compiler          = new Compiler();
        $this->phpParser         = new PhpParser();
        $this->constructor       = new Constructor(
            new Instantiator(
                new ConstructorInstantiatorStrategy(),
                new PropertyInstantiatorStrategy()
            )
        );
        $this->metadataAssembler = (new AnnotationMetadataAssemblerFactory())->build();
    }

    /**
     * @dataProvider validExamples
     */
    public function testAssemblingValidExamples(Reference $reference, Scope $scope, callable $asserter) : void
    {
        $metadata = $this->metadataAssembler->assemble($reference, $scope);

        $asserter($metadata);
    }

    public function validExamples() : iterable
    {
        yield 'fixture - AnnotationTargetAll' => [
            new Reference(AnnotationTargetAll::class, true),
            ScopeMother::withSubject(new ReflectionClass($this)),
            function (AnnotationMetadata $metadata) : void {
                $this->assertEquals(AnnotationTargetAllMetadata::get(), $metadata);
            },
        ];

        yield 'fixture - AnnotationWithRequiredAttributes' => [
            new Reference(AnnotationWithRequiredAttributes::class, true),
            ScopeMother::withSubject(new ReflectionClass($this)),
            function (AnnotationMetadata $metadata) : void {
                $this->assertEquals(AnnotationWithRequiredAttributesMetadata::get(), $metadata);
            },
        ];

        yield 'fixture - AnnotationWithRequiredAttributesWithoutConstructor' => [
            new Reference(AnnotationWithRequiredAttributesWithoutConstructor::class, true),
            ScopeMother::withSubject(new ReflectionClass($this)),
            function (AnnotationMetadata $metadata) : void {
                $this->assertSame(AnnotationWithRequiredAttributesWithoutConstructor::class, $metadata->getName());
                $this->assertTrue($metadata->getTarget()->all(), 'Invalid target');
                $this->assertFalse($metadata->hasConstructor(), 'Has constructor');
                $properties = $metadata->getProperties();
                $expectedProperties = [
                    'value' => new PropertyMetadata(
                        'value',
                        new CompositeConstraint(
                            new TypeConstraint(TestNullableType::fromType(new StringType())),
                            new RequiredConstraint()
                        )
                    ),
                    'annot' => new PropertyMetadata(
                        'annot',
                        new CompositeConstraint(
                            new TypeConstraint(TestNullableType::fromType(new ObjectType(AnnotationTargetAnnotation::class))),
                            new RequiredConstraint()
                        )
                    ),
                ];

                $this->assertEquals(
                    $expectedProperties,
                    $properties
                );
                $this->assertEquals(
                    $expectedProperties['value'],
                    $metadata->getDefaultProperty()
                );
            },
        ];

        yield 'fixture - AnnotationWithVarType' => [
            new Reference(AnnotationWithVarType::class, true),
            ScopeMother::withSubject(new ReflectionClass($this)),
            function (AnnotationMetadata $metadata) : void {
                $this->assertEquals(AnnotationWithVarTypeMetadata::get(), $metadata);
            },
        ];

        yield 'fixture - AnnotationWithConstants' => [
            new Reference(AnnotationWithConstants::class, true),
            ScopeMother::withSubject(new ReflectionClass($this)),
            function (AnnotationMetadata $metadata) : void {
                $this->assertEquals(AnnotationWithConstantsMetadata::get(), $metadata);
            },
        ];

        yield 'fixture - AnnotationEnum' => [
            new Reference(AnnotationEnum::class, true),
            ScopeMother::withSubject(new ReflectionClass($this)),
            function (AnnotationMetadata $metadata) : void {
                $this->assertEquals(AnnotationEnumMetadata::get(), $metadata);
            },
        ];
    }
}
