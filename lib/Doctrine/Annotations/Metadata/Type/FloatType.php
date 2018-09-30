<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Type;

use function is_float;

final class FloatType implements ScalarType
{
    public function describe() : string
    {
        return 'float';
    }

    /**
     * @param mixed $value
     */
    public function validate($value) : bool
    {
        return is_float($value);
    }

    public function acceptsNull() : bool
    {
        return false;
    }
}
