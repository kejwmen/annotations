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
use Doctrine\Annotations\Metadata\Type\NullType;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Annotations\Metadata\Type\UnionType;
use Doctrine\Annotations\Parser\Imports;

final class InternalAnnotations
{
    /**
     * @return iterable<string>
     */
    public static function getNames() : iterable
    {
        yield Annotation::class;
        yield Enum::class;
        yield IgnoreAnnotation::class;
        yield Required::class;
        yield Target::class;
    }

    public static function createMetadata() : MetadataCollection
    {
        return new MetadataCollection(
            new AnnotationMetadata(
                Annotation::class,
                new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                false
            ),
            new AnnotationMetadata(
                Enum::class,
                new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                true,
                [
                    new PropertyMetadata(
                        'value',
                        new ListType(new StringType()),
                        true
                    ),
                    new PropertyMetadata(
                        'literal',
                        new UnionType(new ListType(new StringType()), new NullType())
                    )
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
                        true
                    )
                ]
            ),
            new AnnotationMetadata(
                Required::class,
                new AnnotationTarget(AnnotationTarget::TARGET_PROPERTY),
                false
            ),
            new AnnotationMetadata(
                Target::class,
                new AnnotationTarget(AnnotationTarget::TARGET_ALL),
                true,
                [
                    new PropertyMetadata(
                        'value',
                        new UnionType(new ListType(new StringType()), new NullType()),
                        true
                    ),
                    new PropertyMetadata(
                        'targets',
                        new UnionType(new IntegerType(), new NullType())
                    ),
                    new PropertyMetadata(
                        'literal',
                        new UnionType(new IntegerType(), new NullType())
                    ),
                ]
            )
        );
    }

    public static function createImports() : Imports
    {
        return new Imports([
            'annotation'       => Annotation::class,
            'enum'             => Enum::class,
            'ignoreannotation' => IgnoreAnnotation::class,
            'required'         => Required::class,
            'target'           => Target::class,
        ]);
    }
}
