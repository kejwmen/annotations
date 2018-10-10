<?php
declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Assembler\Validator;

use Doctrine\Annotations\Assembler\Validator\Exception\InvalidValue;
use Doctrine\Annotations\Assembler\Validator\ValueValidator;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Tests\Annotations\Metadata\AnnotationMetadataMother;
use Doctrine\Tests\Annotations\Metadata\Type\PropertyMetadataMother;
use PHPUnit\Framework\TestCase;

class ValueValidatorTest extends TestCase
{
    /** @var ValueValidator */
    private $validator;

    protected function setUp()
    {
        $this->validator = new ValueValidator();
    }

    /**
     * @dataProvider validExamples
     */
    public function testValidates(PropertyMetadata $propertyMetadata, $value): void
    {
        $this->validator->validate(AnnotationMetadataMother::example(), $propertyMetadata, $value);

        $this->assertTrue(true);
    }

    public function validExamples(): iterable
    {
        yield 'valid string' => [
            PropertyMetadataMother::withType(new StringType()),
            'foo'
        ];
    }

    /**
     * @dataProvider invalidExamples
     */
    public function testNotValidatesInvalidExamplesAndThrows(PropertyMetadata $propertyMetadata, $value): void
    {
        $this->expectException(InvalidValue::class);

        $this->validator->validate(AnnotationMetadataMother::example(), $propertyMetadata, $value);
    }

    public function invalidExamples(): iterable
    {
        yield 'value not matching property type' => [
            PropertyMetadataMother::withType(new StringType()),
            42
        ];
    }
}
