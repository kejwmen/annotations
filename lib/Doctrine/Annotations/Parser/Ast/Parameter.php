<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast;

interface Parameter extends Node
{
    public function getValue() : ValuableNode;
}
