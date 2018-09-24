<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Reference;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Scope;
use function assert;
use function class_exists;
use function sprintf;
use function strtolower;

/**
 * @internal
 */
final class FallbackReferenceResolver implements ReferenceResolver
{
    public function resolve(Reference $reference, Scope $scope) : string
    {
        $class = $this->resolveFullyQualifiedName($reference, $scope);

        assert(class_exists($class), sprintf('Class %s not found', $class));

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
