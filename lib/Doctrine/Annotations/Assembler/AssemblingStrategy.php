<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler;

use Doctrine\Annotations\Metadata\AnnotationMetadata;

interface AssemblingStrategy
{
    /**
     * @param iterable<string, mixed> $parameters
     */
    public function construct(AnnotationMetadata $metadata, iterable $parameters) : object;
}
