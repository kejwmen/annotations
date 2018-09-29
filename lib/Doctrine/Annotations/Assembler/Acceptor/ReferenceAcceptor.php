<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Acceptor;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Scope;

interface ReferenceAcceptor
{
    public function accepts(Reference $reference, Scope $scope) : bool;
}
