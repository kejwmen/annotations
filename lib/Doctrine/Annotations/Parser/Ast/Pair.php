<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast;

use Doctrine\Annotations\Parser\Ast\Scalar;
use Doctrine\Annotations\Parser\Visitor\Visitor;

final class Pair implements Value
{
    /** @var Scalar */
    private $key;

    /** @var ValuableNode */
    private $value;

    public function __construct(Scalar $key, ValuableNode $value)
    {
        $this->key   = $key;
        $this->value = $value;
    }

    public function getKey() : Scalar
    {
        return $this->key;
    }

    public function getValue() : ValuableNode
    {
        return $this->value;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitPair($this);
    }
}
