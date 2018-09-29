<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser;

use function array_diff_key;
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

    public function add(string ...$names) : void
    {
        $this->names += array_fill_keys($names, true);
    }

    public function remove(string ...$names) : void
    {
        $this->names = array_diff_key($this->names, array_fill_keys($names, true));
    }
}
