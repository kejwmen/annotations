<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser;

use function array_fill_keys;

final class IgnoredAnnotations
{
    /** @var string[] */
    private $names;

    public function __construct(string ...$names)
    {
        $this->names = array_fill_keys($names, true);
    }

    public function has(string $name) : bool
    {
        return isset($this->names[$name]);
    }

    public function add(string $name) : void
    {
        $this->names[$name] = true;
    }

    public function remove(string $name) : void
    {
        unset($this->names[$name]);
    }
}
