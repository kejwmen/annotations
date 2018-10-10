<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Assembler\Validator;

use Doctrine\Annotations\Assembler\Validator\Exception\InvalidValue;
use Doctrine\Annotations\Assembler\Validator\ValueValidator;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Tests\Annotations\Metadata\AnnotationMetadataMother;
use Doctrine\Tests\Annotations\Metadata\Type\PropertyMetadataMother;
use PHPUnit\Framework\TestCase;

class ValueValidatorTest extends TestCase
{
    /** @var ValueValidator */
    private $validator;

    protected function setUp() : void
    {
        $this->validator = new ValueValidator();
    }

    /**
     * @param mixed $value
     *
     * @dataProvider validExamples
     */
    public function testValidate(PropertyMetadata $propertyMetadata, $value) : void
    {
        $this->validator->validate(AnnotationMetadataMother::example(), $propertyMetadata, $value);

        $this->assertTrue(true);
    }

    /**
     * @return mixed[]
     */
    public function validExamples() : iterable
    {
        yield 'valid string' => [
            PropertyMetadataMother::withType(new StringType()),
            'foo',
        ];
    }

    /**
     * @param mixed $value
     *
     * @dataProvider invalidExamples
     */
    public function testNotValidatesInvalidExamplesAndThrows(PropertyMetadata $propertyMetadata, $value) : void
    {
        $this->expectException(InvalidValue::class);

        $this->validator->validate(AnnotationMetadataMother::example(), $propertyMetadata, $value);
    }

    /**
     * @return mixed[]
     */
    public function invalidExamples() : iterable
    {
        yield 'value not matching property type' => [
            PropertyMetadataMother::withType(new StringType()),
            42,
        ];
    }
}
