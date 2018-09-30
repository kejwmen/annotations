<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Type;

interface Type
{
    public function describe() : string;

    /**
     * @param mixed $value
     */
    public function validate($value) : bool;

    public function acceptsNull() : bool;
}
