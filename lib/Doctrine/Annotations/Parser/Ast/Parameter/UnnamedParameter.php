<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast\Parameter;

use Doctrine\Annotations\Parser\Ast\Parameter;
use Doctrine\Annotations\Parser\Ast\ValuableNode;
use Doctrine\Annotations\Parser\Visitor\Visitor;

final class UnnamedParameter implements Parameter
{
    /** @var ValuableNode */
    private $value;

    public function __construct(ValuableNode $value)
    {
        $this->value = $value;
    }

    public function getValue() : ValuableNode
    {
        return $this->value;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitUnnamedParameter($this);
    }
}
