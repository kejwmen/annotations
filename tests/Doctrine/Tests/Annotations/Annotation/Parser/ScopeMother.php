<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Annotation\Parser;

use Doctrine\Annotations\Parser\Scope;

final class ScopeMother
{
    public static function example(): Scope
    {
        return (new ScopeBuilder())
            ->build();
    }

    public static function withSubject(\Reflector $reflector): Scope
    {
        return (new ScopeBuilder())
            ->withSubject($reflector)
            ->build();
    }

    /**
     * @param string[] $names
     */
    public static function withIgnoredAnnotations(array $names): Scope
    {
        return (new ScopeBuilder())
            ->withIgnoredAnnotations($names)
            ->build();
    }

    /**
     * @param array<string,string> $importsMap
     */
    public static function withImports(array $importsMap): Scope
    {
        return (new ScopeBuilder())
            ->withImports($importsMap)
            ->build();
    }

    public static function withNestingLevel(int $level): Scope
    {
        return (new ScopeBuilder())
            ->withNestingLevel($level)
            ->build();
    }
}
