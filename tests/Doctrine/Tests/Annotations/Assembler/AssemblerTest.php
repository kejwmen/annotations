<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Assembler;

use Doctrine\Annotations\Assembler\Assembler;
use Doctrine\Annotations\Constructor\Constructor;
use Doctrine\Annotations\Constructor\Instantiator\ConstructorInstantiatorStrategy;
use Doctrine\Annotations\Constructor\Instantiator\Instantiator;
use Doctrine\Annotations\Constructor\Instantiator\PropertyInstantiatorStrategy;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\MetadataCollection;
use Doctrine\Annotations\Metadata\Reflection\DefaultReflectionProvider;
use Doctrine\Annotations\Parser\Compiler;
use Doctrine\Annotations\Parser\IgnoredAnnotations;
use Doctrine\Annotations\Parser\Imports;
use Doctrine\Annotations\Parser\Reference\FallbackReferenceResolver;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Annotations\PhpParser;
use Doctrine\Tests\Annotations\Assembler\Acceptor\AlwaysAcceptingAcceptor;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAll;
use Doctrine\Tests\Annotations\Fixtures\ClassWithAnnotationTargetAll;
use Doctrine\Tests\Annotations\Fixtures\Metadata\AnnotationTargetAllMetadata;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use function iterator_to_array;

class AssemblerTest extends TestCase
{
    /** @var Compiler */
    private $compiler;

    /** @var PhpParser */
    private $phpParser;

    public function setUp() : void
    {
        $this->compiler  = new Compiler();
        $this->phpParser = new PhpParser();
    }

    /**
     * @param AnnotationMetadata[] $metadata
     *
     * @dataProvider validExamples
     */
    public function testAssemblingValidExamples(string $class, array $metadata, callable $asserter) : void
    {
        $reflection = new ReflectionClass($class);
        $ast        = $this->compiler->compile($reflection->getDocComment());
        $scope      = $this->createScope($reflection);

        $metadataCollection = new MetadataCollection(...$metadata);

        $assembler = $this->createAssembler($metadataCollection);

        $result = $assembler->collect($ast, $scope);

        $asserter(iterator_to_array($result));
    }

    /**
     * @return mixed[]
     */
    public function validExamples() : iterable
    {
        yield 'Class with AnnotationTargetAll' => [
            ClassWithAnnotationTargetAll::class,
            [AnnotationTargetAllMetadata::get()],
            function (array $result) : void {
                $this->assertCount(1, $result);
                /** @var AnnotationTargetAll $resultAnnotation */
                $resultAnnotation = $result[0];
                $this->assertInstanceOf(AnnotationTargetAll::class, $resultAnnotation);
                $this->assertSame(123, $resultAnnotation->name);
            },
        ];
    }

    private function createScope(ReflectionClass $reflection) : Scope
    {
        return new Scope(
            $reflection,
            new Imports($this->phpParser->parseClass($reflection)),
            new IgnoredAnnotations()
        );
    }

    private function createAssembler(MetadataCollection $collection) : Assembler
    {
        return new Assembler(
            $collection,
            new FallbackReferenceResolver(),
            new Constructor(
                new Instantiator(
                    new ConstructorInstantiatorStrategy(),
                    new PropertyInstantiatorStrategy()
                )
            ),
            new DefaultReflectionProvider(),
            new AlwaysAcceptingAcceptor()
        );
    }
}
