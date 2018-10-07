<?php
declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Assembler\Validator;

use Doctrine\Annotations\Assembler\Validator\TargetValidator;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationTarget;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Tests\Annotations\Annotation\Parser\ScopeMother;
use Doctrine\Tests\Annotations\Metadata\AnnotationMetadataMother;
use PHPUnit\Framework\TestCase;

class TargetValidatorTest extends TestCase
{
    /** @var TargetValidator */
    private $validator;

    public function setUp()
    {
        $this->validator = new TargetValidator();
    }

    /**
     * @dataProvider validExamples
     */
    public function testValidatesValidExamples(AnnotationMetadata $metadata, Scope $scope)
    {
        $this->validator->validate($metadata, $scope);

        $this->assertTrue(true);
    }

    public function validExamples(): iterable
    {
        /** @var \Reflector[] $reflectors */
        $reflectors = [
            new \ReflectionClass(self::class),
            new \ReflectionProperty(self::class, 'validator'),
            new \ReflectionMethod(self::class, 'setUp')
        ];

        foreach ($reflectors as $reflector) {
            yield 'TargetAll for reflector ' . get_class($reflector) => [
                AnnotationMetadataMother::withTarget(new AnnotationTarget(AnnotationTarget::TARGET_ALL)),
                ScopeMother::withSubject($reflector)
            ];
        }

        foreach ([AnnotationTarget::TARGET_ALL, AnnotationTarget::TARGET_ANNOTATION] as $target) {
            yield sprintf('Target of value %d for nested scope', $target) => [
                AnnotationMetadataMother::withTarget(new AnnotationTarget($target)),
                ScopeMother::withNestingLevel(2)
            ];
        }

        $matchingReflectors = [
            AnnotationTarget::TARGET_CLASS => $reflectors[0],
            AnnotationTarget::TARGET_PROPERTY => $reflectors[1],
            AnnotationTarget::TARGET_METHOD => $reflectors[2]
        ];

        foreach ($matchingReflectors as $target => $reflector) {
            yield sprintf('Target of value %d for reflector %s', $target, get_class($reflector)) => [
                AnnotationMetadataMother::withTarget(new AnnotationTarget($target)),
                ScopeMother::withSubject($reflector)
            ];

            yield sprintf('Target of value %d for reflector %s', AnnotationTarget::TARGET_ALL, get_class($reflector)) => [
                AnnotationMetadataMother::withTarget(new AnnotationTarget(AnnotationTarget::TARGET_ALL)),
                ScopeMother::withSubject($reflector)
            ];
        }
    }
}
