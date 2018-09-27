<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler;

use Doctrine\Annotations\Metadata\AnnotationMetadata;

final class ConstructorStrategy implements AssemblingStrategy
{
    /**
     * @param mixed[] $parameters iterable<string, mixed>
     */
    public function construct(AnnotationMetadata $metadata, iterable $parameters) : object
    {
        $class = $metadata->getName();
        return new $class($parameters);
    }
}
