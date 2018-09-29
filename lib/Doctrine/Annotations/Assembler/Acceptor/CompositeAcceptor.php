<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Acceptor;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Scope;

final class CompositeAcceptor implements ReferenceAcceptor
{
    /** @var ReferenceAcceptor[] */
    private $acceptors;

    public function __construct(ReferenceAcceptor ...$acceptors)
    {
        $this->acceptors = $acceptors;
    }

    public function accepts(Reference $reference, Scope $scope) : bool
    {
        foreach ($this->acceptors as $acceptor) {
            if (! $acceptor->accepts($reference, $scope)) {
                return false;
            }
        }

        return true;
    }
}
