<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations;

use Doctrine\Annotations\Annotation;
use Doctrine\Annotations\Assembler\Constant\ReflectionConstantResolver;
use Doctrine\Annotations\Metadata\MetadataCollection;
use Doctrine\Annotations\Metadata\Reflection\DefaultReflectionProvider;
use Doctrine\Annotations\NewAnnotationReader;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAll;
use Doctrine\Tests\Annotations\Fixtures\ClassWithAnnotationTargetAll;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class NewAnnotationReaderTest extends TestCase
{
    /** @var MetadataCollection */
    private $collection;

    /** @var NewAnnotationReader */
    private $reader;

    public function setUp() : void
    {
        $this->collection = new MetadataCollection();
        $this->reader     = new NewAnnotationReader(
            $this->collection,
            new DefaultReflectionProvider(),
            new ReflectionConstantResolver(new DefaultReflectionProvider())
        );
    }

    /**
     * @param Annotation[] $expected
     *
     * @dataProvider examples
     */
    public function testGetClassAnnotations(string $class, array $expected) : void
    {
        $class = new ReflectionClass($class);

        $annotations = $this->reader->getClassAnnotations($class);

        $this->assertEquals(
            $expected,
            $annotations
        );
    }

    /**
     * @return mixed[]
     */
    public function examples() : iterable
    {
        yield 'ClassWithAnnotationTargetAll' => [
            ClassWithAnnotationTargetAll::class,
            [$this->createAnnotationTargetAll('123')],
        ];
    }

    private function createAnnotationTargetAll(?string $name = null) : AnnotationTargetAll
    {
        $annotation = new AnnotationTargetAll();

        $annotation->name = $name;

        return $annotation;
    }
}
