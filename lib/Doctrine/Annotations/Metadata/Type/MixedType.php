<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Type;

use function is_array;
use function is_scalar;

final class MixedType implements Type
{
    public function describe() : string
    {
        return 'mixed';
    }

    public function validate($value) : bool
    {
        return is_scalar($value) || is_array($value);
    }
}
