<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Constructor;

final class PropertyPopulator
{
    /**
     * @param mixed[] $parameters
     */
    public function populate(object $instance, iterable $parameters) : void
    {
        foreach ($parameters as $name => $value) {
            $instance->$name = $value;
        }
    }
}
