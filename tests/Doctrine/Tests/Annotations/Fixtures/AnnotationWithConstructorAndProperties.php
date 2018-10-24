<?php

namespace Doctrine\Tests\Annotations\Fixtures;

/**
 * @Annotation
 * @Target("ALL")
 */
final class AnnotationWithConstructorAndProperties
{
    /**
     * @var mixed[]
     */
    private $values;

    /**
     * @var string
     */
    public $foo;

    /**
     * @var int
     */
    public $bar;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * @return mixed[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
