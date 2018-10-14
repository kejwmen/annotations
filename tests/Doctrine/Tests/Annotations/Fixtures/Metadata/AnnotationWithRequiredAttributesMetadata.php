<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Fixtures\Metadata;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationTarget;
use Doctrine\Annotations\Metadata\Constraint\CompositeConstraint;
use Doctrine\Annotations\Metadata\Constraint\RequiredConstraint;
use Doctrine\Annotations\Metadata\Constraint\TypeConstraint;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use Doctrine\Annotations\Metadata\Type\ObjectType;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAnnotation;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithRequiredAttributes;
use Doctrine\Tests\Annotations\Metadata\Type\TestNullableType;

final class AnnotationWithRequiredAttributesMetadata
{
    public static function get(): AnnotationMetadata
    {
        return new AnnotationMetadata(
            AnnotationWithRequiredAttributes::class,
            new AnnotationTarget(AnnotationTarget::TARGET_ALL),
            true,
            [
                new PropertyMetadata(
                    'value',
                    new CompositeConstraint(
                        new TypeConstraint(TestNullableType::fromType(new StringType())),
                        new RequiredConstraint()
                    )
                ),
                new PropertyMetadata(
                    'annot',
                    new CompositeConstraint(
                        new TypeConstraint(TestNullableType::fromType(
                            new ObjectType(AnnotationTargetAnnotation::class)
                        )),
                        new RequiredConstraint()
                    )
                )
            ]
        );
    }
}
