<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Constant\Exception;

use RuntimeException;
use function sprintf;

final class StandaloneConstantNotFound extends RuntimeException implements ConstantResolutionException
{
    public static function new(string $constantName) : self
    {
        return new self(
            sprintf('Constant %s does not exist.', $constantName)
        );
    }
}
