<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Ast;

use Doctrine\Annotations\Parser\Visitor\Visitor;

final class Reference implements Node
{
    /** @var string */
    private $identifier;

    /** @var bool */
    private $fullyQualified;

    public function __construct(string $identifier, bool $fullyQualified)
    {
        $this->identifier     = $identifier;
        $this->fullyQualified = $fullyQualified;
    }

    public function getIdentifier() : string
    {
        return $this->identifier;
    }

    public function isFullyQualified() : bool
    {
        return $this->fullyQualified;
    }

    public function dispatch(Visitor $visitor) : void
    {
        $visitor->visitReference($this);
    }
}
