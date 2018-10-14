<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Fixtures\Metadata;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationTarget;
use Doctrine\Annotations\Metadata\Constraint\TypeConstraint;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use Doctrine\Annotations\Metadata\Type\IntegerType;
use Doctrine\Annotations\Metadata\Type\MixedType;
use Doctrine\Annotations\Metadata\Type\NullType;
use Doctrine\Annotations\Metadata\Type\UnionType;
use Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAll;

final class AnnotationTargetAllMetadata
{
    public static function get(): AnnotationMetadata
    {
        return new AnnotationMetadata(
            AnnotationTargetAll::class,
            new AnnotationTarget(AnnotationTarget::TARGET_ALL),
            false,
            [
                new PropertyMetadata(
                    'data',
                    new TypeConstraint(new MixedType())
                ),
                new PropertyMetadata(
                    'name',
                    new TypeConstraint(new MixedType())
                ),
                new PropertyMetadata(
                    'target',
                    new TypeConstraint(new MixedType())
                ),
            ]
        );
    }
}
