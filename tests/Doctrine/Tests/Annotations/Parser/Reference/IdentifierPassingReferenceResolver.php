<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Parser\Reference;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Reference\ReferenceResolver;
use Doctrine\Annotations\Parser\Scope;

final class IdentifierPassingReferenceResolver implements ReferenceResolver
{
    public function resolve(Reference $reference, Scope $scope) : string
    {
        return $reference->getIdentifier();
    }
}
