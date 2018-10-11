<?php

namespace Doctrine\Annotations\Metadata\Assembler;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Scope;

/**
 * @internal
 */
interface AnnotationMetadataAssembler
{
    public function assemble(Reference $reference, Scope $scope): AnnotationMetadata;
}