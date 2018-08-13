<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser;

use ArrayAccess;
use IteratorAggregate;
use function assert;
use function is_string;

final class Imports implements ArrayAccess, IteratorAggregate
{
    /** @var string[] alias => FCQN */
    private $map = [];

    public function __construct(iterable $map)
    {
        foreach ($map as $alias => $name) {
            assert(is_string($alias) && is_string($name));
            assert(!isset($this[$alias]));

            $this->map[$alias] = $name;
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
        assert(isset($this[$offset]));

        return $this->map[$offset];
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
        return isset($this->map[$offset]);
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset) : void
    {
        assert(false, 'immutable');
    }
}
