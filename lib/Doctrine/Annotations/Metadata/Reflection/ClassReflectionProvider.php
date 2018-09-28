<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Reflection;

use ReflectionClass;
use Doctrine\Annotations\Metadata\Reflection\Exception\InvalidClass;

interface ClassReflectionProvider
{
    /**
     * @throws InvalidClass
     */
    public function getClassReflection(string $name) : ReflectionClass;
}
