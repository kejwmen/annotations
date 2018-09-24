<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

use Doctrine\Annotations\Annotation\Annotation;
use Doctrine\Annotations\Annotation\Enum;
use Doctrine\Annotations\Annotation\IgnoreAnnotation;
use Doctrine\Annotations\Annotation\Required;
use Doctrine\Annotations\Annotation\Target;
use Doctrine\Annotations\Metadata\Type\IntegerType;
use Doctrine\Annotations\Metadata\Type\ListType;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Annotations\Parser\Imports;

final class InternalAnnotations
{
    public static function createMetadata() : MetadataCollection
    {
        return new MetadataCollection(
            new AnnotationMetadata(
                Annotation::class,
                new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                false,
                []
            ),
            new AnnotationMetadata(
                Enum::class,
                new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                true,
                [
                    'value' => new PropertyMetadata(
                        'value',
                        new ListType(new StringType()),
                        true,
                        true
                    ),
                    'literal' => new PropertyMetadata(
                        'literal',
                        new ListType(new StringType()),
                        false
                    ),
                ]
            ),
            new AnnotationMetadata(
                IgnoreAnnotation::class,
                new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                true,
                [
                    new PropertyMetadata(
                        'names',
                        new ListType(new StringType()),
                        true,
                        true
                    ),
                ]
            ),
            new AnnotationMetadata(
                Required::class,
                new AnnotationTarget(AnnotationTarget::TARGET_PROPERTY),
                false,
                []
            ),
            new AnnotationMetadata(
                Target::class,
                new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                true,
                [
                    new PropertyMetadata(
                        'value',
                        new ListType(new StringType()),
                        false,
                        true
                    ),
                    new PropertyMetadata(
                        'targets',
                        new IntegerType(),
                        false
                    ),
                    new PropertyMetadata(
                        'literal',
                        new IntegerType(),
                        false
                    ),
                ]
            )
        );
    }

    public static function createImports() : Imports
    {
        return new Imports([
            'Annotation'       => Annotation::class,
            'Enum'             => Enum::class,
            'IgnoreAnnotation' => IgnoreAnnotation::class,
            'Required'         => Required::class,
            'Target'           => Target::class,
        ]);
    }
}
