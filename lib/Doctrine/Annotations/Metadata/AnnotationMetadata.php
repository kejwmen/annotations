<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

use function array_combine;
use function array_filter;
use function array_map;
use function array_values;
use function count;

final class AnnotationMetadata
{
    /** @var string */
    private $name;

    /** @var AnnotationTarget */
    private $target;

    /** @var bool */
    private $hasConstructor;

    /** @var PropertyMetadata[] */
    private $properties;

    /** @var PropertyMetadata|null */
    private $defaultProperty;

    /**
     * TODO: Validate input
     *
     * @param PropertyMetadata[] $properties
     */
    public function __construct(
        string $name,
        AnnotationTarget $target,
        bool $hasConstructor,
        array $properties
    ) {
        $this->name            = $name;
        $this->target          = $target;
        $this->hasConstructor  = $hasConstructor;
        $this->properties      = array_combine(
            array_map(function (PropertyMetadata $property) : string {
                return $property->getName();
            }, $properties),
            $properties
        );

        $this->defaultProperty = array_values(array_filter($properties, function (PropertyMetadata $property) : bool {
            return $property->isDefault();
        }))[0] ?? null;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getTarget() : AnnotationTarget
    {
        return $this->target;
    }

    public function hasConstructor() : bool
    {
        return $this->hasConstructor;
    }

    /**
     * @return PropertyMetadata[]
     */
    public function getProperties() : array
    {
        return $this->properties;
    }

    public function getDefaultProperty() : ?PropertyMetadata
    {
        return $this->defaultProperty;
    }
}
