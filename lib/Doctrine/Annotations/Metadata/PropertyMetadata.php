<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

use Doctrine\Annotations\Metadata\Type\Type;

final class PropertyMetadata
{
    /** @var string */
    private $name;

    /** @var Type */
    private $type;

    /** @var bool */
    private $isRequired;

    /** @var bool */
    private $isDefault;

    /** @var mixed[] */
    private $enumValues;

    /**
     * @param mixed[] $enumValues
     */
    public function __construct(string $name, Type $type, array $enumValues = [], bool $isRequired = false, bool $isDefault = false)
    {
        $this->name       = $name;
        $this->type       = $type;
        $this->enumValues = $enumValues;
        $this->isRequired = $isRequired;
        $this->isDefault  = $isDefault;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function type() : Type
    {
        return $this->type;
    }

    public function isRequired() : bool
    {
        return $this->isRequired;
    }

    public function isDefault() : bool
    {
        return $this->isDefault;
    }

    /**
     * @return mixed[]
     */
    public function enumValues() : array
    {
        return $this->enumValues;
    }
}
