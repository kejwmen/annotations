<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Acceptor;

use Doctrine\Annotations\Metadata\InternalAnnotations;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Reference\Exception\ReferenceNotResolvable;
use Doctrine\Annotations\Parser\Reference\ReferenceResolver;
use Doctrine\Annotations\Parser\Scope;

final class InternalAcceptor implements ReferenceAcceptor
{
    /** @var ReferenceResolver */
    private $referenceResolver;

    public function __construct(ReferenceResolver $referenceResolver)
    {
        $this->referenceResolver = $referenceResolver;
    }

    public function accepts(Reference $reference, Scope $scope) : bool
    {
        try {
            $name = $this->referenceResolver->resolve($reference, $scope);
        } catch (ReferenceNotResolvable $e) {
            return false;
        }


        foreach (InternalAnnotations::getNames() as $internalName) {
            if ($internalName === $name) {
                return true;
            }
        }

        return false;
    }
}
