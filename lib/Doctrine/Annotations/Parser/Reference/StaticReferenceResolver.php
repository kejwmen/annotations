<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Reference;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Reference\Exception\ReferenceNotResolvable;
use Doctrine\Annotations\Parser\Scope;

final class StaticReferenceResolver implements ReferenceResolver
{
    public function resolve(Reference $reference, Scope $scope) : string
    {
        if ($reference->isFullyQualified()) {
            if (! $scope->getImports()->isKnown($reference->getIdentifier())) {
                throw ReferenceNotResolvable::new($reference);
            }

            return $reference->getIdentifier();
        }

        if (! isset($scope->getImports()[$reference->getIdentifier()])) {
            throw ReferenceNotResolvable::new($reference);
        }

        return $scope->getImports()[$reference->getIdentifier()];
    }
}
