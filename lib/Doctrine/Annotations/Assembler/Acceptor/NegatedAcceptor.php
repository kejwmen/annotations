<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Acceptor;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Scope;

final class NegatedAcceptor implements ReferenceAcceptor
{
    /** @var ReferenceAcceptor */
    private $acceptor;

    public function __construct(ReferenceAcceptor $acceptor)
    {
        $this->acceptor = $acceptor;
    }

    public function accepts(Reference $reference, Scope $scope) : bool
    {
        return ! $this->acceptor->accepts($reference, $scope);
    }
}
