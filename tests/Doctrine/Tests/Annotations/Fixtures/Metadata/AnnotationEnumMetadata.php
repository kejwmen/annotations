<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Fixtures\Metadata;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationTarget;
use Doctrine\Annotations\Metadata\Constraint\CompositeConstraint;
use Doctrine\Annotations\Metadata\Constraint\EnumConstraint;
use Doctrine\Annotations\Metadata\Constraint\TypeConstraint;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use Doctrine\Annotations\Metadata\Type\IntegerType;
use Doctrine\Annotations\Metadata\Type\MixedType;
use Doctrine\Annotations\Metadata\Type\NullType;
use Doctrine\Annotations\Metadata\Type\UnionType;
use Doctrine\Tests\Annotations\Fixtures\AnnotationEnum;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAll;

final class AnnotationEnumMetadata
{
    public static function get(): AnnotationMetadata
    {
        return new AnnotationMetadata(
            AnnotationEnum::class,
            new AnnotationTarget(AnnotationTarget::TARGET_ALL),
            false,
            [
                new PropertyMetadata(
                    'value',
                    new CompositeConstraint(
                        new TypeConstraint(new MixedType()),
                        new EnumConstraint([
                            AnnotationEnum::ONE,
                            AnnotationEnum::TWO,
                            AnnotationEnum::THREE,
                        ])
                    ),
                    true
                )
            ]
        );
    }
}
