<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Type;

use function is_string;

final class StringType implements ScalarType
{
    public function describe() : string
    {
        return 'string';
    }

    /**
     * @param mixed $value
     */
    public function validate($value) : bool
    {
        return is_string($value);
    }
}
