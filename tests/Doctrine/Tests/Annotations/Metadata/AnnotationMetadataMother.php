<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\AnnotationTarget;

final class AnnotationMetadataMother
{
    public static function withTarget(AnnotationTarget $target): AnnotationMetadata
    {
        return new AnnotationMetadata(
            'foo',
            $target,
            false,
            []
        );
    }
}
