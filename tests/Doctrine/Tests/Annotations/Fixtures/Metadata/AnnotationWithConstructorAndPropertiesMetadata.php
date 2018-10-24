<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Fixtures\Metadata;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationTarget;
use Doctrine\Annotations\Metadata\Constraint\TypeConstraint;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use Doctrine\Annotations\Metadata\Type\IntegerType;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithConstructorAndProperties;

final class AnnotationWithConstructorAndPropertiesMetadata
{
    public static function get(): AnnotationMetadata
    {
        return new AnnotationMetadata(
            AnnotationWithConstructorAndProperties::class,
            new AnnotationTarget(AnnotationTarget::TARGET_ALL),
            true,
            [
                new PropertyMetadata('foo', new TypeConstraint(new StringType())),
                new PropertyMetadata('bar', new TypeConstraint(new IntegerType()))
            ]
        );
    }
}
