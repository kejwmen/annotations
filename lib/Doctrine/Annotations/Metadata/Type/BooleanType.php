<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Type;

use function is_bool;

final class BooleanType implements ScalarType
{
    public function describe() : string
    {
        return 'boolean';
    }

    /**
     * @param mixed $value
     */
    public function validate($value) : bool
    {
        return is_bool($value);
    }
}
