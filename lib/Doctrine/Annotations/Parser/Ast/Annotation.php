<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast;

use Doctrine\Annotations\Parser\Visitor\Visitor;

final class Annotation implements ValuableNode
{
    /** @var Reference */
    private $name;

    /** @var Parameters */
    private $parameters;

    public function __construct(Reference $name, Parameters $parameters)
    {
        $this->name       = $name;
        $this->parameters = $parameters;
    }

    public function getName() : Reference
    {
        return $this->name;
    }

    public function getParameters() : Parameters
    {
        return $this->parameters;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitAnnotation($this);
    }
}
