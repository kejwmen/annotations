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
    private $mandatory;

    /** @var bool */
    private $default;

    public function __construct(string $name, Type $type, bool $mandatory, bool $default = false)
    {
        $this->name      = $name;
        $this->type      = $type;
        $this->mandatory = $mandatory;
        $this->default   = $default;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : Type
    {
        return $this->type;
    }

    public function isMandatory() : bool
    {
        return $this->mandatory;
    }

    public function isDefault() : bool
    {
        return $this->default;
    }
}
