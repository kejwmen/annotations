<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Type;

use function is_int;

final class IntegerType implements ScalarType
{
    public function describe() : string
    {
        return 'integer';
    }

    /**
     * @param mixed $value
     */
    public function validate($value) : bool
    {
        return is_int($value);
    }
}
