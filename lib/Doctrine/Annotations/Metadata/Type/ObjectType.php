<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Type;

use function is_object;

final class ObjectType implements Type
{
    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function describe() : string
    {
        return $this->name;
    }

    public function validate($value) : bool
    {
        return is_object($value) && $value instanceof $this->name;
    }

    public function acceptsNull() : bool
    {
        return false;
    }
}
