<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Fixtures\Metadata;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationTarget;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use Doctrine\Annotations\Metadata\Type\ObjectType;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAnnotation;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithRequiredAttributesWithoutConstructor;
use Doctrine\Tests\Annotations\Metadata\Type\TestNullableType;

final class AnnotationWithRequiredAttributesWithoutConstructorMetadata
{
    public static function get(): AnnotationMetadata
    {
        return new AnnotationMetadata(
            AnnotationWithRequiredAttributesWithoutConstructor::class,
            new AnnotationTarget(AnnotationTarget::TARGET_ALL),
            false,
            [
                new PropertyMetadata(
                    'value',
                    TestNullableType::fromType(new StringType()),
                    [],
                    true
                ),
                new PropertyMetadata(
                    'annot',
                    TestNullableType::fromType(new ObjectType(AnnotationTargetAnnotation::class)),
                    [],
                    true
                )
            ]
        );
    }
}
