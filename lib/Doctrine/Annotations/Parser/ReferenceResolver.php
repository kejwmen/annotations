<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser;

use Doctrine\Annotations\Parser\Ast\Reference;
use function assert;
use function class_exists;
use function strpos;

/**
 * @internal
 */
final class ReferenceResolver
{
    public function resolve(Reference $reference, Scope $scope) : string
    {
        $class = $this->resolveFullyQualifiedName($reference, $scope);

        assert(class_exists($class), 'class not found');

        return $class;
    }

    private function resolveFullyQualifiedName(Reference $reference, Scope $scope) : string
    {
        $identifier = $reference->getIdentifier();

        if ($reference->isFullyQualified()) {
            return $identifier;
        }

        $namespace = $scope->getSubject()->getNamespaceName();

        if ($namespace === '') {
            return $imports[$identifier] ?? $identifier;
        }

        if (strpos($identifier, '\\') === false) {
            return $imports[$identifier] ?? $namespace . '\\' . $identifier;
        }

        return $namespace . '\\' . $identifier;
    }
}
