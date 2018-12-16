<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Constant;

use Doctrine\Annotations\Assembler\Constant\Exception\ConstantResolutionException;

interface ConstantResolver
{
    /**
     * @return mixed
     *
     * @throws ConstantResolutionException
     */
    public function resolveClassOrInterfaceConstant(string $holderName, string $constantName);

    /**
     * @return mixed
     *
     * @throws ConstantResolutionException
     */
    public function resolveStandaloneConstant(string $constantName);
}
