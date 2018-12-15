<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Assembler\Validator;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\InvalidTarget;
use Doctrine\Annotations\Parser\Scope;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

final class TargetValidator
{
    /**
     * @throws InvalidTarget
     */
    public function validate(AnnotationMetadata $metadata, Scope $scope) : void
    {
        $target = $metadata->getTarget();

        if ($target->all()) {
            return;
        }

        if ($scope->isNested()) {
            if (! $target->annotation()) {
                throw InvalidTarget::annotation($metadata);
            }

            return;
        }

        $subject = $scope->getSubject();

        if ($subject instanceof ReflectionClass && ! $target->class()) {
            throw InvalidTarget::class($metadata);
        }

        if ($subject instanceof ReflectionProperty && ! $target->property()) {
            throw InvalidTarget::property($metadata);
        }

        if ($subject instanceof ReflectionMethod && ! $target->method()) {
            throw InvalidTarget::method($metadata);
        }

        // TODO validate annotation target
    }
}
