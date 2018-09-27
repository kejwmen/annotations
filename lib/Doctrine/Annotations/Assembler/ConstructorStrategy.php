<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler;

use Doctrine\Annotations\Metadata\AnnotationMetadata;

final class ConstructorStrategy implements AssemblingStrategy
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function construct(AnnotationMetadata $metadata, iterable $parameters) : object
    {
        $class = $metadata->getName();
        return new $class($parameters);
    }
}
