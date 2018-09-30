<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Type;

final class MapType implements CompositeType
{
    /** @var ScalarType */
    private $keyType;

    /** @var Type */
    private $valueType;

    public function __construct(ScalarType $keyType, Type $valueType)
    {
        $this->keyType   = $keyType;
        $this->valueType = $valueType;
    }

    public function getKeyType() : ScalarType
    {
        return $this->keyType;
    }

    public function getValueType() : Type
    {
        return $this->valueType;
    }

    public function describe() : string
    {
        return sprintf('array<%s, %s>', $this->keyType->describe(), $this->valueType->describe());
    }

    public function validate($value) : bool
    {
        if (! is_array($value)) {
            return false;
        }

        foreach ($value as $key => $innerValue) {
            if (! $this->keyType->validate($key)) {
                return false;
            }

            if (! $this->valueType->validate($innerValue)) {
                return false;
            }
        }

        return true;
    }

    public function acceptsNull() : bool
    {
        return $this->valueType->acceptsNull();
    }
}
