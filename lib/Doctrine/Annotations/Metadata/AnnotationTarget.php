<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

use Doctrine\Annotations\Annotation\Target;

final class AnnotationTarget
{
    public const TARGET_CLASS      = 1;
    public const TARGET_METHOD     = 2;
    public const TARGET_PROPERTY   = 4;
    public const TARGET_ANNOTATION = 8;
    public const TARGET_ALL        = self::TARGET_CLASS
        | self::TARGET_METHOD
        | self::TARGET_PROPERTY
        | self::TARGET_ANNOTATION;

    /** @var int */
    private $target;

    public function __construct(int $target)
    {
        $this->target = $target;
    }

    public static function fromAnnotation(Target $annotation) : self
    {
        return new self($annotation->targets);
    }

    public function get() : int
    {
        return $this->target;
    }

    public function getName() : string
    {
        return $this->name;
    }
}
