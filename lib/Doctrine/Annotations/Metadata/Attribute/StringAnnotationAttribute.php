<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Attribute;

final class StringAnnotationAttribute extends AbstractAnnotationAttribute
{
    private const TYPE = 'string';

    public function __construct(string $name, bool $isRequired, string $value)
    {
        parent::__construct($name, $isRequired, self::TYPE, $value);
    }
}
