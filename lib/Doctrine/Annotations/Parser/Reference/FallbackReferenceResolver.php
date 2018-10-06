<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Reference;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Scope;
use function strtolower;

/**
 * @internal
 */
final class FallbackReferenceResolver implements ReferenceResolver
{
    public function resolve(Reference $reference, Scope $scope) : string
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

        $subject = $scope->getSubject();

        if (!$subject instanceof \ReflectionClass || !$subject instanceof \ReflectionFunctionAbstract) {
            return $identifier;
        }

        $namespace = $subject->getNamespaceName();

        if ($namespace === '') {
            return $identifier;
        }

        return $namespace . '\\' . $identifier;
    }
}
