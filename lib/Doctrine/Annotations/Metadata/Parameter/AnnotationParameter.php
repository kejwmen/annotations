<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Parameter;

interface AnnotationParameter
{
    /**
     * @return mixed
     */
    public function getValue();
}
