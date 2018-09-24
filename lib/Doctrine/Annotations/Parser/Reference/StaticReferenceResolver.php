<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Reference;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Scope;
use function assert;
use function sprintf;

final class StaticReferenceResolver implements ReferenceResolver
{
    public function resolve(Reference $reference, Scope $scope) : string
    {
        if ($reference->isFullyQualified()) {
            assert($scope->getImports()->isKnown($reference->getIdentifier()), sprintf('%s not known', $reference->getIdentifier()));
            return $reference->getIdentifier();
        }

        assert(isset($scope->getImports()[$reference->getIdentifier()]), sprintf('%s unresolvable', $reference->getIdentifier()));

        return $scope->getImports()[$reference->getIdentifier()];
    }
}
