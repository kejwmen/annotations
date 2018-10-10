<?php
declare(strict_types=1);

namespace Doctrine\Tests\Annotations;

use Doctrine\Annotations\Metadata\MetadataCollection;
use Doctrine\Annotations\Metadata\Reflection\DefaultReflectionProvider;
use Doctrine\Annotations\NewAnnotationReader;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAll;
use PHPUnit\Framework\TestCase;

class NewAnnotationReaderTest extends TestCase
{
    /** @var MetadataCollection */
    private $collection;

    /** @var NewAnnotationReader */
    private $reader;

    public function setUp()
    {
        $this->collection = new MetadataCollection();
        $this->reader = new NewAnnotationReader(
            $this->collection,
            new DefaultReflectionProvider()
        );
    }

    /**
     * @dataProvider examples
     */
    public function testGetClassAnnotations(string $class, array $expected)
    {
        $class = new \ReflectionClass($class);

        $annotations = $this->reader->getClassAnnotations($class);

        $this->assertEquals(
            $expected,
            iterator_to_array($annotations)
        );
    }

    public function examples(): iterable
    {
        yield 'ClassWithAnnotationTargetAll' => [
            ClassWithAnnotationTargetAll::class,
            [$this->createAnnotationTargetAll(123)]
        ];
    }

    private function createAnnotationTargetAll($name = null)
    {
        $annotation = new AnnotationTargetAll();

        $annotation->name = $name;

        return $annotation;
    }
}

/**
 * @AnnotationTargetAll(name=123)
 */
class ClassWithAnnotationTargetAll
{
}
