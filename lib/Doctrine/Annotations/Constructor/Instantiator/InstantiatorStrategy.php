<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Constructor\Instantiator;

use Doctrine\Annotations\Metadata\AnnotationMetadata;

interface InstantiatorStrategy
{
    /**
     * @param mixed[] $parameters array<string, mixed>
     */
    public function construct(AnnotationMetadata $metadata, array $parameters) : object;
}
