<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser;

use ArrayAccess;
use IteratorAggregate;
use function assert;
use function in_array;
use function is_string;
use function strtolower;

final class Imports implements ArrayAccess, IteratorAggregate
{
    /** @var array<string, string> alias => FCQN */
    private $map = [];

    /**
     * @param iterable<string, string> $map
     */
    public function __construct(iterable $map)
    {
        foreach ($map as $alias => $name) {
            assert(is_string($alias) && is_string($name));
            assert(! isset($this[strtolower($alias)]));

            $this->map[strtolower($alias)] = $name;
        }
    }

    /**
     * @return string[] alias => FCQN
     */
    public function getIterator() : iterable
    {
        yield from $this->map;
    }

    public function offsetGet(string $offset) : string
    {
        assert(isset($this[strtolower($offset)]));

        return $this->map[strtolower($offset)];
    }

    public function offsetSet(string $offset, string $value) : void
    {
        assert(false, 'immutable');
    }

    public function offsetExists(string $offset) : bool
    {
        return isset($this->map[strtolower($offset)]);
    }

    public function offsetUnset(string $offset) : void
    {
        assert(false, 'immutable');
    }

    public function isKnown(string $name) : bool
    {
        return in_array($name, $this->map, true);
    }
}
