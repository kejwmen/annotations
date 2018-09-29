<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Constructor\Instantiator;

use Doctrine\Annotations\Metadata\AnnotationMetadata;

interface InstantiatorStrategy
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function construct(AnnotationMetadata $metadata, array $parameters) : object;
}
