<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Constant;

use Doctrine\Annotations\Assembler\Constant\Exception\ClassConstantNotFound;
use Doctrine\Annotations\Assembler\Constant\Exception\ConstantNotAccessible;
use Doctrine\Annotations\Assembler\Constant\Exception\ConstantResolutionException;
use Doctrine\Annotations\Assembler\Constant\Exception\InvalidClass;
use Doctrine\Annotations\Assembler\Constant\Exception\StandaloneConstantNotFound;
use Doctrine\Annotations\Metadata\Reflection\ClassReflectionProvider;
use ReflectionException;
use function assert;
use function constant;
use function defined;
use function strpos;

final class ReflectionConstantResolver implements ConstantResolver
{
    /** @var ClassReflectionProvider */
    private $classReflectionProvider;

    public function __construct(ClassReflectionProvider $classReflectionProvider)
    {
        $this->classReflectionProvider = $classReflectionProvider;
    }

    /**
     * @return mixed
     *
     * @throws ConstantResolutionException
     */
    public function resolveClassOrInterfaceConstant(string $holderName, string $constantName)
    {
        try {
            $classReflection = $this->classReflectionProvider->getClassReflection($holderName);
        } catch (ReflectionException $e) {
            throw InvalidClass::new($holderName, $constantName);
        }

        $constantReflection = $classReflection->getReflectionConstant($constantName);

        if ($constantReflection === false) {
            throw ClassConstantNotFound::new($holderName, $constantName);
        }

        if (! $constantReflection->isPublic()) {
            throw ConstantNotAccessible::new($holderName, $constantName);
        }

        return $constantReflection->getValue();
    }

    /**
     * @return mixed
     *
     * @throws ConstantResolutionException
     */
    public function resolveStandaloneConstant(string $constantName)
    {
        assert(strpos($constantName, '::') === false);

        if (! defined($constantName)) {
            throw StandaloneConstantNotFound::new($constantName);
        }

        return constant($constantName);
    }
}
