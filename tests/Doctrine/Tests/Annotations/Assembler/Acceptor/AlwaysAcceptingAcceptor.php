<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Assembler\Acceptor;

use Doctrine\Annotations\Assembler\Acceptor\ReferenceAcceptor;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Scope;

final class AlwaysAcceptingAcceptor implements ReferenceAcceptor
{
    public function accepts(Reference $reference, Scope $scope) : bool
    {
        return true;
    }
}
