<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Attribute;

interface AnnotationAttribute
{
    public function getName() : string;

    public function isRequired() : bool;

    public function getType() : string;

    public function getValue() : string;
}
