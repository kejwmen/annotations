<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

use Doctrine\Annotations\Metadata\Attribute\AnnotationAttribute;
use Doctrine\Annotations\Metadata\Parameter\AnnotationParameter;

final class AnnotationMetadata
{
    /** @var bool */
    private $hasConstructor;

    /** @var string|null */
    private $defaultProperty;

    /** @var AnnotationProperty[] */
    private $properties;

    /** @var AnnotationAttribute[] */
    private $attributes;

    /** @var AnnotationTarget[] */
    private $targets;

    /** @var AnnotationParameter[] */
    private $parameters;

    /**
     * TODO: Validate input
     *
     * @param AnnotationProperty[]  $properties
     * @param AnnotationParameter[] $parameters
     * @param AnnotationAttribute[] $attributes
     * @param AnnotationTarget[]    $targets
     */
    public function __construct(
        bool $hasConstructor,
        ?string $defaultProperty,
        array $parameters,
        array $properties,
        array $attributes,
        array $targets
    ) {
        $this->hasConstructor  = $hasConstructor;
        $this->defaultProperty = $defaultProperty;
        $this->properties      = $properties;
        $this->attributes      = $attributes;
        $this->targets         = $targets;
        $this->parameters      = $parameters;
    }

    public function hasConstructor() : bool
    {
        return $this->hasConstructor;
    }

    public function getDefaultProperty() : ?string
    {
        return $this->defaultProperty;
    }

    /**
     * @return AnnotationProperty[]
     */
    public function getProperties() : array
    {
        return $this->properties;
    }

    /**
     * @return AnnotationAttribute[]
     */
    public function getAttributes() : array
    {
        return $this->attributes;
    }

    /**
     * @return AnnotationTarget[]
     */
    public function getTargets() : array
    {
        return $this->targets;
    }

    /**
     * @return AnnotationParameter[]
     */
    public function getParameters() : array
    {
        return $this->parameters;
    }
}
