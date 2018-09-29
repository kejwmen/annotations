<?php

declare(strict_types=1);

namespace Doctrine\Annotations\TypeParser;

use Doctrine\Annotations\Metadata\Type\Type;

interface TypeParser
{
    public function parsePropertyType(string $docBlock, bool $required) : ?Type;
}
