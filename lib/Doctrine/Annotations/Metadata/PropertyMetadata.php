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

    /** @var mixed[] */
    private $enumValues;

    /**
     * @param mixed[] $enumValues
     */
    public function __construct(string $name, Type $type, array $enumValues = [], bool $isRequired = false)
    {
        $this->name       = $name;
        $this->type       = $type;
        $this->enumValues = $enumValues;
        $this->isRequired = $isRequired;
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

    /**
     * @return mixed[]
     */
    public function enumValues() : array
    {
        return $this->enumValues;
    }
}
