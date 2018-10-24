<?php
declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Constructor\Instantiator;

use Doctrine\Annotations\Constructor\Instantiator\ConstructorInstantiatorStrategy;
use Doctrine\Annotations\Constructor\PropertyPopulator;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithConstructor;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithConstructorAndProperties;
use Doctrine\Tests\Annotations\Fixtures\Metadata\AnnotationWithConstructorAndPropertiesMetadata;
use Doctrine\Tests\Annotations\Fixtures\Metadata\AnnotationWithConstructorMetadata;
use PHPUnit\Framework\TestCase;

final class ConstructorInstantiatorStrategyTest extends TestCase
{
    /** @var ConstructorInstantiatorStrategy */
    private $constructorInstantiatorStrategy;

    public function setUp()
    {
        $this->constructorInstantiatorStrategy = new ConstructorInstantiatorStrategy(
            new PropertyPopulator()
        );
    }

    /**
     * @dataProvider constructingExamplesProvider
     *
     * @param mixed[] $parameters
     */
    public function testConstructsExamples(
        AnnotationMetadata $metadata,
        iterable $parameters,
        callable $expectedResultFactory
    ) {
        $result = $this->constructorInstantiatorStrategy->construct($metadata, $parameters);

        $this->assertEquals($expectedResultFactory(), $result);
    }

    /**
     * @return mixed[]
     */
    public function constructingExamplesProvider(): iterable
    {
        yield 'fixture - AnnotationWithConstructor' => [
            AnnotationWithConstructorMetadata::get(),
            [
                'bar' => 'baz',
                'value' => 'foo',
            ],
            function () {
                return new AnnotationWithConstructor(['value' => 'foo']);
            }
        ];

        yield 'fixture - AnnotationWithConstructorAndProperties' => [
            AnnotationWithConstructorAndPropertiesMetadata::get(),
            [
                'foo' => 'test',
                'bar' => 42,
                'baz' => new \stdClass(),
                'zaz' => 3.14
            ],
            function () {
                $annotation = new AnnotationWithConstructorAndProperties([
                    'baz' => new \stdClass(),
                    'zaz' => 3.14
                ]);

                $annotation->bar = 42;
                $annotation->foo = 'test';

                return $annotation;
            }
        ];
    }
}
