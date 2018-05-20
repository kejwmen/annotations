<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast\Scalar;

use Doctrine\Annotations\Parser\Ast\Scalar;
use Doctrine\Annotations\Parser\Visitor\Visitor;

final class IntegerScalar implements Scalar
{
    /** @var int */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getValue() : int
    {
        return $this->value;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitIntegerScalar($this);
    }
}
