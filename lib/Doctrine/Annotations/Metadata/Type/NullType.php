<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Type;

final class NullType implements ScalarType
{
    public function describe() : string
    {
        return 'null';
    }

    public function validate($value) : bool
    {
        return $value === null;
    }

    public function acceptsNull() : bool
    {
        return true;
    }
}
