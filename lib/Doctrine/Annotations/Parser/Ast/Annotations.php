<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast;

use Doctrine\Annotations\Parser\Visitor\Visitor;
use IteratorAggregate;

final class Annotations implements Node, IteratorAggregate
{
    /** @var Annotation[] */
    private $annotations;

    public function __construct(Annotation ...$annotations)
    {
        $this->annotations = $annotations;
    }

    /**
     * @return Annotation[]
     */
    public function getIterator() : iterable
    {
        yield from $this->annotations;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitAnnotations($this);
    }
}
