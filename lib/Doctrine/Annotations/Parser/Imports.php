<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser;

use ArrayAccess;
use IteratorAggregate;
use function assert;
use function in_array;
use function is_string;

final class Imports implements ArrayAccess, IteratorAggregate
{
    /** @var string[] array<string, string> alias => FCQN */
    private $map = [];

    /**
     * @param string[] $map iterable<string, string>
     */
    public function __construct(iterable $map)
    {
        foreach ($map as $alias => $name) {
            assert(is_string($alias) && is_string($name));
            assert(! isset($this[$alias]));

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
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    public function offsetGet($offset) : string
    {
        assert(isset($this[$offset]));

        return $this->map[$offset];
    }

    /**
     * @param string $offset
     * @param string $value
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    public function offsetSet($offset, $value) : void
    {
        assert(false, 'immutable');
    }

    /**
     * @param string $offset
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    public function offsetExists($offset) : bool
    {
        return isset($this->map[$offset]);
    }

    /**
     * @param string $offset
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    public function offsetUnset($offset) : void
    {
        assert(false, 'immutable');
    }

    public function isKnown(string $name) : bool
    {
        return in_array($name, $this->map, true);
    }
}
