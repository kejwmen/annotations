<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Type;

final class MixedType implements Type
{
    public function describe() : string
    {
        return 'mixed';
    }

    /**
     * @param mixed $value
     */
    public function validate($value) : bool
    {
        return true;
    }

    public function acceptsNull() : bool
    {
        return true;
    }
}
