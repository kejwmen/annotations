<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Reference;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Scope;
use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;
use function assert;
use function explode;
use function strpos;
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

        if (strpos($identifierLower, '\\') !== false) {
            $namespacePart = explode('\\', $identifierLower, 2)[0];

            if (isset($imports[$namespacePart])) {
                return $imports[$namespacePart] . '\\' . explode('\\', $identifier, 2)[1];
            }
        }

        $namespace = $this->getSubjectNamespaceName($scope->getSubject());

        if ($namespace === '') {
            return $identifier;
        }

        return $namespace . '\\' . $identifier;
    }

    private function getSubjectNamespaceName(Reflector $subject) : string
    {
        if ($subject instanceof ReflectionClass || $subject instanceof ReflectionFunction) {
            return $subject->getNamespaceName();
        }

        if ($subject instanceof ReflectionProperty || $subject instanceof ReflectionMethod) {
            return $subject->getDeclaringClass()->getNamespaceName();
        }

        assert(false, 'Unsupported Reflector');
    }
}
