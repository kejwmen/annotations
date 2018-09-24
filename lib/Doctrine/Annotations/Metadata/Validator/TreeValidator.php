<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Validator;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Parser\Ast\Annotation;

interface TreeValidator
{
    /**
     * @throws ValidationFalied
     */
    public function validate(Annotation $annotation, AnnotationMetadata $metadata) :  void;
}
