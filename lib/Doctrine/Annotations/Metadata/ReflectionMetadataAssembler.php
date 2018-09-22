<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

use Doctrine\Annotations\Parser\Ast\Reference;

final class ReflectionMetadataAssembler
{
    /**
     * @var array
     */
    private $aliasesToFqcn;

    public function __construct(array $aliasesToFqcn)
    {
        $this->aliasesToFqcn = $aliasesToFqcn;
    }

    public function get(Reference $reference, array $parameters): AnnotationMetadata
    {
        $fqcn = $this->determineFqcn($reference);

        $reflection = new \ReflectionClass($fqcn);

        $docComment = $reflection->getDocComment();

        $properties = array_map(function (\ReflectionProperty $property) {
            return new AnnotationProperty($property->getName());
        }, $reflection->getProperties(\ReflectionProperty::IS_PUBLIC));

        $attributes = [];

        $targets = [];

        return new AnnotationMetadata(
            false !== strpos($docComment, '@Annotation'),
            null,
            $parameters,
            $properties,
            $attributes,
            $targets
        );
    }

    private function determineFqcn(Reference $reference): string
    {
        if ($reference->isFullyQualified()) {
            return $reference->getIdentifier();
        }

        return $this->aliasesToFqcn[$reference->getIdentifier()];
    }
}
