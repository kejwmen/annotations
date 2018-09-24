<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Reference;

use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Scope;

/**
 * @internal
 */
interface ReferenceResolver
{
    public function resolve(Reference $reference, Scope $scope) : string;
}
