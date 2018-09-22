<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Attribute;

abstract class AbstractAnnotationAttribute implements AnnotationAttribute
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $isRequired;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $value;

    public function __construct(
        string $name,
        bool $isRequired,
        string $type,
        string $value
    ) {
        $this->name = $name;
        $this->isRequired = $isRequired;
        $this->type = $type;
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
