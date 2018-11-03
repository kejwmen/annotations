<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Fixtures\Metadata;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationTarget;
use Doctrine\Tests\Annotations\Fixtures\AnnotationWithRequiredAttributes;

final class AnnotationWithRequiredAttributesMetadata
{
    public static function get(): AnnotationMetadata
    {
        return new AnnotationMetadata(
            AnnotationWithRequiredAttributes::class,
            new AnnotationTarget(AnnotationTarget::TARGET_ALL),
            true
        );
    }
}
