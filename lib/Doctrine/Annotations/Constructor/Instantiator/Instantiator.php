<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Constructor\Instantiator;

use Doctrine\Annotations\Metadata\AnnotationMetadata;

final class Instantiator
{
    /** @var ConstructorInstantiatorStrategy */
    private $constructor;

    /** @var PropertyInstantiatorStrategy */
    private $property;

    public function __construct(ConstructorInstantiatorStrategy $constructor, PropertyInstantiatorStrategy $property)
    {
        $this->constructor = $constructor;
        $this->property    = $property;
    }

    /**
     * @param mixed[] $parameters array<string, mixed>
     */
    public function instantiate(AnnotationMetadata $metadata, iterable $parameters) : object
    {
        if ($metadata->hasConstructor()) {
            return $this->constructor->construct($metadata, $parameters);
        }

        return $this->property->construct($metadata, $parameters);
    }
}
