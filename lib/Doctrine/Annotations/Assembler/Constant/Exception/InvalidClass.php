<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Constant\Exception;

use RuntimeException;
use function sprintf;

final class InvalidClass extends RuntimeException implements ConstantResolutionException
{
    public static function new(string $class, string $constantName) : self
    {
        return new self(
            sprintf(
                'Class or interface %1$s not found while looking up constant %1$s::%2$s.',
                $class,
                $constantName
            )
        );
    }
}
