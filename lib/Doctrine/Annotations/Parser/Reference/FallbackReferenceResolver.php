<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Reference;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Reference\Exception\ReferenceNotResolvable;
use Doctrine\Annotations\Parser\Scope;
use function class_exists;
use function strtolower;

/**
 * @internal
 */
final class FallbackReferenceResolver implements ReferenceResolver
{
    public function resolve(Reference $reference, Scope $scope) : string
    {
        $class = $this->resolveFullyQualifiedName($reference, $scope);

        if (! class_exists($class)) {
            throw ReferenceNotResolvable::new($reference);
        }

        return $class;
    }

    private function resolveFullyQualifiedName(Reference $reference, Scope $scope) : string
    {
        $identifier = $reference->getIdentifier();
        $imports    = $scope->getImports();

        if ($reference->isFullyQualified()) {
            return $identifier;
        }

        $identifierLower = strtolower($identifier);

        if (isset($imports[$identifierLower])) {
            return $imports[$identifierLower];
        }

        $namespace = $scope->getSubject()->getNamespaceName();

        if ($namespace === '') {
            return $identifier;
        }

        return $namespace . '\\' . $identifier;
    }
}
