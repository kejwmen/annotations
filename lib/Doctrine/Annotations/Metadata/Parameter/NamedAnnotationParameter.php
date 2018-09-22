<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Parameter;

final class NamedAnnotationParameter implements AnnotationParameter
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var mixed
     */
    private $value;

    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue()
    {
        return $this->value;
    }
}
