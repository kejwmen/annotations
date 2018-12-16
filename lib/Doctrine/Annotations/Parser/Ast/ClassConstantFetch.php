<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast;

use Doctrine\Annotations\Parser\Ast\Scalar\Identifier;
use Doctrine\Annotations\Parser\Visitor\Visitor;

final class ClassConstantFetch implements ValuableNode
{
    /** @var Reference */
    private $class;

    /** @var Identifier */
    private $name;

    public function __construct(Reference $class, Identifier $name)
    {
        $this->class = $class;
        $this->name  = $name;
    }

    public function getClass() : Reference
    {
        return $this->class;
    }

    public function getName() : Identifier
    {
        return $this->name;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitClassConstantFetch($this);
    }
}
