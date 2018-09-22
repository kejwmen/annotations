<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

final class AnnotationProperty
{
    /**
     * @var string
     */
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
