<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast;

use Countable;
use IteratorAggregate;

interface Collection extends Value, IteratorAggregate, Countable
{
    /**
     * @return Value[]
     */
    public function getIterator() : iterable;

    public function count() : int;
}
