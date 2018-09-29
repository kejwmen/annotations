<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Constructor\Instantiator;

use Doctrine\Annotations\Metadata\AnnotationMetadata;

final class PropertyInstantiatorStrategy implements InstantiatorStrategy
{
    /**
     * @param mixed[] $parameters array<string, mixed>
     */
    public function construct(AnnotationMetadata $metadata, array $parameters) : object
    {
        $class      = $metadata->getName();
        $annotation = new $class();

        foreach ($parameters as $name => $value) {
            if ($name === '') {
                $name = $metadata->getDefaultProperty()->getName();
            }

            $annotation->$name = $value;
        }

        return $annotation;
    }
}
