<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast;

use IteratorAggregate;

interface Collection extends Value, IteratorAggregate
{
    /**
     * @return Value[]
     */
    public function getIterator() : iterable;
}
