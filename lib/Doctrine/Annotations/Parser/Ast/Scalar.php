<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast;

interface Scalar extends Value
{
    /**
     * @return mixed
     */
    public function getValue();
}
