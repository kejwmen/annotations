<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast\Collection;

use Doctrine\Annotations\Parser\Ast\Collection;
use Doctrine\Annotations\Parser\Ast\ValuableNode;
use Doctrine\Annotations\Parser\Visitor\Visitor;

final class ListCollection implements Collection
{
    /** @var ValuableNode[] */
    private $items;

    public function __construct(ValuableNode ...$items)
    {
        $this->items = $items;
    }

    /**
     * @return ValuableNode[]
     */
    public function getIterator() : iterable
    {
        yield from $this->items;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitListCollection($this);
    }
}
