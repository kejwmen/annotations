<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Exception;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use InvalidArgumentException;
use function array_map;
use function implode;
use function sprintf;

final class MultipleDefaultProperties extends InvalidArgumentException
{
    /**
     * @param PropertyMetadata[] $properties
     */
    public static function new(AnnotationMetadata $metadata, array $properties) : self
    {
        return new self(sprintf(
            'Cannot define multiple default properties "%s" for annotation %s',
            implode(',', array_map(static function (PropertyMetadata $propertyMetadata) {
                return $propertyMetadata->getName();
            }, $properties)),
            $metadata->getName()
        ));
    }
}
