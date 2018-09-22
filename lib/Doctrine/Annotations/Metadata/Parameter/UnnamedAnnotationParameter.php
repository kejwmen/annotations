<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Parameter;

final class UnnamedAnnotationParameter implements AnnotationParameter
{
    /**
     * @var mixed
     */
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
