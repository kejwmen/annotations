<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Constant\Exception;

use RuntimeException;
use function sprintf;

final class ClassConstantNotFound extends RuntimeException implements ConstantResolutionException
{
    public static function new(string $holderName, string $constantName) : self
    {
        return new self(
            sprintf('Class or interface constant %s::%s does not exist.', $holderName, $constantName)
        );
    }
}
