<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Annotation\Parser;

use Doctrine\Annotations\Parser\IgnoredAnnotations;
use Doctrine\Annotations\Parser\Imports;
use Doctrine\Annotations\Parser\Scope;

final class ScopeMother
{
    public static function example(): Scope
    {
        return new Scope(
            new \ReflectionClass(self::class),
            new Imports([]),
            new IgnoredAnnotations()
        );
    }

    public static function withReflector(\Reflector $reflector): Scope
    {
        return new Scope(
            $reflector,
            new Imports([]),
            new IgnoredAnnotations()
        );
    }

    /**
     * @param string[] $names
     */
    public static function withIgnoredAnnotations(array $names): Scope
    {
        return new Scope(
            new \ReflectionClass(self::class),
            new Imports([]),
            new IgnoredAnnotations(...$names)
        );
    }

    /**
     * @param array<string,string> $importsMap
     */
    public static function withImports(array $importsMap): Scope
    {
        return new Scope(
            new \ReflectionClass(self::class),
            new Imports($importsMap),
            new IgnoredAnnotations()
        );
    }
}
