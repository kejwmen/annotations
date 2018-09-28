<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Reflection;

use Doctrine\Annotations\Metadata\Reflection\Exception\InvalidClass;
use ReflectionClass;

interface ClassReflectionProvider
{
    /**
     * @throws InvalidClass
     */
    public function getClassReflection(string $name) : ReflectionClass;
}
