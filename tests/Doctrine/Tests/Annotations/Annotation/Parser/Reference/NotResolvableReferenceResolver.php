<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Annotation\Parser\Reference;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Reference\Exception\ReferenceNotResolvable;
use Doctrine\Annotations\Parser\Reference\ReferenceResolver;
use Doctrine\Annotations\Parser\Scope;

final class NotResolvableReferenceResolver implements ReferenceResolver
{
    public function resolve(Reference $reference, Scope $scope): string
    {
        throw ReferenceNotResolvable::new($reference);
    }
}
