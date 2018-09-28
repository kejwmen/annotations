<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser;

use ReflectionClass;
use ReflectionFunctionAbstract;
use ReflectionProperty;
use Reflector;
use function assert;

/**
 * Represents the source scope effective at the phpDoc declaration.
 *
 * @internal
 */
final class Scope
{
    /** @var ReflectionClass|ReflectionProperty|ReflectionFunctionAbstract */
    private $subject;

    /** @var Imports */
    private $imports;

    /** @var IgnoredAnnotations */
    private $ignoredAnnotations;

    public function __construct(Reflector $subject, Imports $imports, IgnoredAnnotations $ignoredAnnotations)
    {
        assert(
            $subject instanceof ReflectionClass
            || $subject instanceof ReflectionProperty
            || $subject instanceof ReflectionFunctionAbstract
        );

        $this->subject            = $subject;
        $this->imports            = $imports;
        $this->ignoredAnnotations = $ignoredAnnotations;
    }

    /**
     * @return ReflectionClass|ReflectionFunctionAbstract|ReflectionProperty
     */
    public function getSubject() : Reflector
    {
        return $this->subject;
    }

    public function getImports() : Imports
    {
        return $this->imports;
    }

    public function getIgnoredAnnotations() : IgnoredAnnotations
    {
        return $this->ignoredAnnotations;
    }
}
