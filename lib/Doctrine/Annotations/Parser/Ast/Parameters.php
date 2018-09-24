<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast;

use Countable;
use Doctrine\Annotations\Parser\Visitor\Visitor;
use IteratorAggregate;
use function count;

final class Parameters implements Node, IteratorAggregate, Countable
{
    /** @var Parameter[] */
    private $parameters;

    public function __construct(Parameter ...$parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return Parameter[]
     */
    public function getIterator() : iterable
    {
        yield from $this->parameters;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitParameters($this);
    }

    public function count() : int
    {
        return count($this->parameters);
    }
}
