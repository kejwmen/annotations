<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler;

use Doctrine\Annotations\Metadata\AnnotationMetadata;

interface AssemblingStrategy
{
    /**
     * @param string[] $parameters iterable<string, mixed>
     */
    public function construct(AnnotationMetadata $metadata, iterable $parameters) : object;
}
