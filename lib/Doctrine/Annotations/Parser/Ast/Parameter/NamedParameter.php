<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast\Parameter;

use Doctrine\Annotations\Parser\Ast\Parameter;
use Doctrine\Annotations\Parser\Ast\Scalar\Identifier;
use Doctrine\Annotations\Parser\Ast\ValuableNode;
use Doctrine\Annotations\Parser\Visitor\Visitor;

final class NamedParameter implements Parameter
{
    /** @var Identifier */
    private $name;

    /** @var ValuableNode */
    private $value;

    public function __construct(Identifier $name, ValuableNode $value)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    public function getName() : Identifier
    {
        return $this->name;
    }

    public function getValue() : ValuableNode
    {
        return $this->value;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitNamedParameter($this);
    }
}
