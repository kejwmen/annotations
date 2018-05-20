<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast\Scalar;

use Doctrine\Annotations\Parser\Ast\Scalar;
use Doctrine\Annotations\Parser\Visitor\Visitor;

final class BooleanScalar implements Scalar
{
    /** @var bool */
    private $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function getValue() : bool
    {
        return $this->value;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitBooleanScalar($this);
    }
}
