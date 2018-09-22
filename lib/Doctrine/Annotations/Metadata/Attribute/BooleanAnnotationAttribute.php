<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Attribute;

final class BooleanAnnotationAttribute extends AbstractAnnotationAttribute
{
    private const TYPE = 'boolean';

    public function __construct(string $name, bool $isRequired, string $value)
    {
        parent::__construct($name, $isRequired, self::TYPE, $value);
    }
}
