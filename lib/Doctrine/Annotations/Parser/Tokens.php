<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser;

final class Tokens
{
    public const BOOLEAN               = 'boolean';
    public const FLOAT                 = 'float';
    public const IDENTIFIER            = 'identifier';
    public const INTEGER               = 'integer';
    public const NAMESPACED_IDENTIFIER = 'identifier_ns';
    public const NULL                  = 'null';

    private function __construct()
    {
    }
}
