<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast\Collection;

use Doctrine\Annotations\Parser\Ast\Collection;
use Doctrine\Annotations\Parser\Ast\Pair;
use Doctrine\Annotations\Parser\Visitor\Visitor;

final class MapCollection implements Collection
{
    /** @var Pair[] */
    private $pairs;

    public function __construct(Pair ...$pairs)
    {
        $this->pairs = $pairs;
    }

    /**
     * @return Pair[]
     */
    public function getIterator() : iterable
    {
        yield from $this->pairs;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitMapCollection($this);
    }
}
