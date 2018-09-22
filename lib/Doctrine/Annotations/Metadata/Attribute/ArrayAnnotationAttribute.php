<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Attribute;

final class ArrayAnnotationAttribute extends AbstractAnnotationAttribute
{
    private const TYPE = 'array_type';

    /** @var string|null */
    private $arrayType;

    public function __construct(
        string $name,
        bool $isRequired,
        string $value,
        ?string $arrayType
    ) {
        parent::__construct($name, $isRequired, self::TYPE, $value);

        $this->arrayType = $arrayType;
    }

    public function getArrayType() : ?string
    {
        return $this->arrayType;
    }
}
