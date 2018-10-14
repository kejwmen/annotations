<?php
declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Constructor;

use Doctrine\Annotations\Constructor\Constructor;
use Doctrine\Annotations\Constructor\Instantiator\ConstructorInstantiatorStrategy;
use Doctrine\Annotations\Constructor\Instantiator\Instantiator;
use Doctrine\Annotations\Constructor\Instantiator\PropertyInstantiatorStrategy;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Tests\Annotations\Annotation\Parser\ScopeMother;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithConstructor;
use Doctrine\Tests\Annotations\Fixtures\Metadata\AnnotationWithConstructorMetadata;
use PHPUnit\Framework\TestCase;

class ConstructorTest extends TestCase
{
    /** @var Constructor */
    private $constructor;

    public function setUp()
    {
        $this->constructor = new Constructor(
            new Instantiator(
                new ConstructorInstantiatorStrategy(),
                new PropertyInstantiatorStrategy()
            )
        );
    }

    /**
     * @dataProvider validExamples
     */
    public function testCreatesAnnotation(
        AnnotationMetadata $annotationMetadata,
        Scope $scope,
        iterable $parameters,
        callable $asserter
    ) {
        $result = $this->constructor->construct($annotationMetadata, $scope, $parameters);

        $asserter($result);
    }

    public function validExamples(): iterable
    {
        yield 'with constructor' => [
            AnnotationWithConstructorMetadata::get(),
            ScopeMother::example(),
            ['value' => 'foo'],
            function (AnnotationWithConstructor $result) {
                $this->assertSame('foo', $result->getValue());
            }
        ];
    }
}
