<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast\Scalar;

use Doctrine\Annotations\Parser\Ast\Scalar;
use Doctrine\Annotations\Parser\Visitor\Visitor;

final class StringScalar implements Scalar
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getValue() : string
    {
        return $this->value;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitStringScalar($this);
    }
}
