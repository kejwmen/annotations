<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler;

use Doctrine\Annotations\Metadata\AnnotationMetadata;

final class PropertyStrategy implements AssemblingStrategy
{
    /**
     * @param mixed[] $parameters iterable<string, mixed>
     */
    public function construct(AnnotationMetadata $metadata, iterable $parameters) : object
    {
        $class      = $metadata->getName();
        $annotation = new $class();

        foreach ($parameters as $name => $value) {
            $annotation->$name = $value;
        }

        return $annotation;
    }
}
