<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Parser;

use Doctrine\Annotations\Parser\IgnoredAnnotations;
use Doctrine\Annotations\Parser\Imports;
use Doctrine\Annotations\Parser\Scope;
use ReflectionClass;
use Reflector;

final class ScopeBuilder
{
    /** @var Reflector */
    private $subject;

    /** @var Imports */
    private $imports;

    /** @var IgnoredAnnotations */
    private $ignoredAnnotations;

    /** @var int */
    private $nestingLevel;

    public function __construct()
    {
        $this->subject            = new ReflectionClass(self::class);
        $this->imports            = new Imports([]);
        $this->ignoredAnnotations = new IgnoredAnnotations();
        $this->nestingLevel       = 0;
    }

    public function withSubject(Reflector $reflector) : self
    {
        $this->subject = $reflector;

        return $this;
    }

    /**
     * @param string[] $map
     */
    public function withImports(array $map) : self
    {
        $this->imports = new Imports($map);

        return $this;
    }

    /**
     * @param string[] $names
     */
    public function withIgnoredAnnotations(array $names) : self
    {
        $this->ignoredAnnotations = new IgnoredAnnotations(...$names);

        return $this;
    }

    public function withNestingLevel(int $level) : self
    {
        $this->nestingLevel = $level;

        return $this;
    }

    public function build() : Scope
    {
        return new Scope(
            $this->subject,
            $this->imports,
            $this->ignoredAnnotations,
            $this->nestingLevel
        );
    }
}
