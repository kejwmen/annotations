<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Type;

use function is_array;
use function sprintf;

final class ListType implements Type
{
    /** @var Type */
    private $valueType;

    public function __construct(Type $valueType)
    {
        $this->valueType = $valueType;
    }

    public function getValueType() : Type
    {
        return $this->valueType;
    }

    public function describe() : string
    {
        return sprintf('array<%s>', $this->valueType->describe());
    }

    public function validate($value) : bool
    {
        if (! is_array($value)) {
            return false;
        }

        $i = 0;
        foreach ($value as $key => $innerValue) {
            if ($key !== $i) {
                return false;
            }

            if (! $this->valueType->validate($innerValue)) {
                return false;
            }

            $i++;
        }

        return true;
    }
}
