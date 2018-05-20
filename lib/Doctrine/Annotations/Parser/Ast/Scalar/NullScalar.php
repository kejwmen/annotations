<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast\Scalar;

use Doctrine\Annotations\Parser\Ast\Scalar;
use Doctrine\Annotations\Parser\Visitor\Visitor;

final class NullScalar implements Scalar
{
    /**
     * @return null
     */
    public function getValue()
    {
        return null;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitNullScalar($this);
    }
}
