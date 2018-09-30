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
            assert(!isset($this[strtolower($alias)]));

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

    /**
     * @param string $offset
     */
    public function offsetGet($offset) : string
    {
        assert(isset($this[strtolower($offset)]));

        return $this->map[strtolower($offset)];
    }

    /**
     * @param string $offset
     * @param string $value
     */
    public function offsetSet($offset, $value) : void
    {
        assert(false, 'immutable');
    }

    /**
     * @param string $offset
     */
    public function offsetExists($offset) : bool
    {
        return isset($this->map[strtolower($offset)]);
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset) : void
    {
        assert(false, 'immutable');
    }

    public function isKnown(string $name) : bool
    {
        return in_array(strtolower($name), $this->map, true);
    }
}
