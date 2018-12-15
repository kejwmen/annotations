<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

use function array_combine;
use function array_filter;
use function array_map;
use function reset;

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
     * @param PropertyMetadata[] $properties
     */
    public function __construct(
        string $name,
        AnnotationTarget $target,
        bool $hasConstructor,
        array $properties = []
    ) {
        $this->name           = $name;
        $this->target         = $target;
        $this->hasConstructor = $hasConstructor;
        $this->properties     = array_combine(
            array_map(static function (PropertyMetadata $property) : string {
                return $property->getName();
            }, $properties),
            $properties
        );

        $defaultProperties = array_filter($this->properties, static function (PropertyMetadata $metadata) {
            return $metadata->isDefault();
        });

        if (count($defaultProperties) > 1) {
            throw new \InvalidArgumentException('Cannot define multiple default properties');
        }

        $defaultProperty = reset($defaultProperties);
        $firstProperty   = reset($this->properties);

        if ($defaultProperty) {
            $this->defaultProperty = $defaultProperty;
        }

        if ($this->defaultProperty !== null || ! $firstProperty) {
            return;
        }

        // TODO: Revisit removal for 3.0
        $this->defaultProperty = $firstProperty;
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
