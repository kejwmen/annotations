<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser;

final class Nodes
{
    public const ANNOTATION         = '#annotation';
    public const ANNOTATIONS        = '#annotations';
    public const CONSTANT           = '#constant';
    public const LIST               = '#list';
    public const MAP                = '#map';
    public const NAMED_PARAMETERS   = '#named_parameter';
    public const PAIR               = '#pair';
    public const PARAMETERS         = '#parameters';
    public const REFERENCE          = '#reference';
    public const STRING             = '#string';
    public const UNNAMED_PARAMETERS = '#unnamed_parameter';
    public const VALUE              = '#value';

    private function __construct()
    {
    }
}
