<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Fixtures\Metadata;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationTarget;
use Doctrine\Annotations\Assembler\Validator\Constraint\TypeConstraint;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use Doctrine\Annotations\Metadata\Type\MixedType;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithConstants;

final class AnnotationWithConstantsMetadata
{
    public static function get(): AnnotationMetadata
    {
        return new AnnotationMetadata(
            AnnotationWithConstants::class,
            new AnnotationTarget(AnnotationTarget::TARGET_ALL),
            false,
            [
                // TODO: Add other properties
                new PropertyMetadata(
                    'value',
                    new MixedType()
                )
            ]
        );
    }
}
