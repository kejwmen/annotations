<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationTarget;
use Doctrine\Annotations\Metadata\InvalidTarget;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Tests\Annotations\Annotation\Parser\ScopeMother;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;
use function get_class;
use function sprintf;

class AnnotationMetadataTest extends TestCase
{
    /**
     * @dataProvider targetsWithMatchingScopeExamples
     */
    public function testValidatesTargetForMatchingScope(AnnotationTarget $target, Scope $scope) : void
    {
        $metadata = AnnotationMetadataMother::withTarget($target);

        $metadata->validateTarget($scope);

        $this->assertTrue(true);
    }

    public function targetsWithMatchingScopeExamples() : iterable
    {
        /** @var Reflector[] $reflectors */
        $reflectors = [
            new ReflectionClass(self::class),
            new ReflectionProperty(AnnotationMetadata::class, 'name'),
            new ReflectionMethod(self::class, 'setUp'),
        ];

        foreach ($reflectors as $reflector) {
            yield 'TargetAll for reflector ' . get_class($reflector) => [
                new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                ScopeMother::withSubject($reflector),
            ];
        }

        foreach ([AnnotationTarget::TARGET_ALL, AnnotationTarget::TARGET_ANNOTATION] as $target) {
            yield sprintf('Target of value %d for nested scope', $target) => [
                new AnnotationTarget($target),
                ScopeMother::withNestingLevel(2),
            ];
        }

        $matchingReflectors = [
            AnnotationTarget::TARGET_CLASS => $reflectors[0],
            AnnotationTarget::TARGET_PROPERTY => $reflectors[1],
            AnnotationTarget::TARGET_METHOD => $reflectors[2],
        ];

        foreach ($matchingReflectors as $target => $reflector) {
            yield sprintf('Target of value %d for reflector %s', $target, get_class($reflector)) => [
                new AnnotationTarget($target),
                ScopeMother::withSubject($reflector),
            ];

            yield sprintf('Target of value %d for reflector %s', AnnotationTarget::TARGET_ALL, get_class($reflector)) => [
                new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                ScopeMother::withSubject($reflector),
            ];
        }
    }

    /**
     * @dataProvider targetsWithNotMatchingScopeExamples
     */
    public function testValidatesTargetForNotMatchingScopeAndThrows(AnnotationTarget $target, Scope $scope) : void
    {
        $metadata = AnnotationMetadataMother::withTarget($target);

        $this->expectException(InvalidTarget::class);

        $metadata->validateTarget($scope);
    }

    public function targetsWithNotMatchingScopeExamples() : iterable
    {
        foreach ([AnnotationTarget::TARGET_CLASS, AnnotationTarget::TARGET_METHOD, AnnotationTarget::TARGET_PROPERTY] as $target) {
            yield sprintf('Target of value %d for nested scope', $target) => [
                new AnnotationTarget($target),
                ScopeMother::withNestingLevel(2),
            ];
        }

        $notMatchingReflectors = [
            AnnotationTarget::TARGET_CLASS => new ReflectionMethod(self::class, 'setUp'),
            AnnotationTarget::TARGET_METHOD => new ReflectionProperty(AnnotationMetadata::class, 'name'),
            AnnotationTarget::TARGET_PROPERTY => new ReflectionClass(self::class),
        ];

        foreach ($notMatchingReflectors as $target => $reflector) {
            yield sprintf('Target of value %d for reflector %s', $target, get_class($reflector)) => [
                new AnnotationTarget($target),
                ScopeMother::withSubject($reflector),
            ];
        }
    }
}
