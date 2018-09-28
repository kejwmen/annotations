<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

use Doctrine\Annotations\ImplicitIgnoredAnnotationNames;
use Doctrine\Annotations\Parser\IgnoredAnnotations;
use Doctrine\Annotations\Parser\Imports;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Annotations\PhpParser;
use ReflectionClass;
use ReflectionProperty;

final class ScopeManufacturer
{
    /** @var PhpParser */
    private $phpParser;

    public function __construct(PhpParser $phpParser)
    {
        $this->phpParser = $phpParser;
    }

    public function manufactureClassScope(ReflectionClass $class) : Scope
    {
        return new Scope(
            $class,
            $this->collectImports($class),
            new IgnoredAnnotations(...ImplicitIgnoredAnnotationNames::LIST)
        );
    }

    public function manufacturePropertyScope(ReflectionProperty $property) : Scope
    {
        return new Scope(
            $property,
            $this->collectImports($property->getDeclaringClass()),
            new IgnoredAnnotations(...ImplicitIgnoredAnnotationNames::LIST)
        );
    }

    private function collectImports(ReflectionClass $class) : Imports
    {
        return new Imports($this->phpParser->parseClass($class));
    }
}
