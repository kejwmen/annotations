<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast;

use Doctrine\Annotations\Parser\Visitor\Visitor;

/**
 * @internal
 */
interface Node
{
    public function dispatch(Visitor $visitor) : void;
}
