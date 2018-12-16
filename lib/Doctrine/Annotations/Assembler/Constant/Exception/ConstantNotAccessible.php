<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Constant\Exception;

use ReflectionException;
use function sprintf;

final class ConstantNotAccessible extends ReflectionException implements ConstantResolutionException
{
    public static function new(string $className, string $constantName) : self
    {
        return new self(
            sprintf("Could not access constant %s::%s because it's not public.", $className, $constantName)
        );
    }
}
