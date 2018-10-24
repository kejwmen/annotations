<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Constructor;

use Doctrine\Annotations\Constructor\Constructor;
use Doctrine\Annotations\Constructor\Instantiator\ConstructorInstantiatorStrategy;
use Doctrine\Annotations\Constructor\Instantiator\Instantiator;
use Doctrine\Annotations\Constructor\Instantiator\PropertyInstantiatorStrategy;
use Doctrine\Annotations\Constructor\PropertyPopulator;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithConstructor;
use Doctrine\Tests\Annotations\Fixtures\Metadata\AnnotationWithConstructorMetadata;
use Doctrine\Tests\Annotations\Parser\ScopeMother;
use PHPUnit\Framework\TestCase;

class ConstructorTest extends TestCase
{
    /** @var Constructor */
    private $constructor;

    public function setUp() : void
    {
        $propertyPopulator = new PropertyPopulator();

        $this->constructor = new Constructor(
            new Instantiator(
                new ConstructorInstantiatorStrategy($propertyPopulator),
                new PropertyInstantiatorStrategy($propertyPopulator)
            )
        );
    }

    /**
     * @param mixed[] $parameters
     *
     * @dataProvider validExamples
     */
    public function testCreatesAnnotation(
        AnnotationMetadata $annotationMetadata,
        Scope $scope,
        iterable $parameters,
        callable $asserter
    ) : void {
        $result = $this->constructor->construct($annotationMetadata, $scope, $parameters);

        $asserter($result);
    }

    /**
     * @return mixed[]
     */
    public function validExamples() : iterable
    {
        yield 'with constructor' => [
            AnnotationWithConstructorMetadata::get(),
            ScopeMother::example(),
            ['value' => 'foo'],
            function (AnnotationWithConstructor $result) : void {
                $this->assertSame('foo', $result->getValue());
            },
        ];
    }
}
