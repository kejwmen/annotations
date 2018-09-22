<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

use Doctrine\Annotations\Metadata\Parameter\AnnotationParameter;
use Doctrine\Annotations\Parser\Ast\Reference;
use ReflectionClass;
use ReflectionProperty;
use function array_map;
use function strpos;

final class ReflectionMetadataAssembler
{
    /** @var string[] */
    private $aliasesToFqcn;

    /**
     * @param string[] $aliasesToFqcn
     */
    public function __construct(array $aliasesToFqcn)
    {
        $this->aliasesToFqcn = $aliasesToFqcn;
    }

    /**
     * @param AnnotationParameter[] $parameters
     */
    public function get(Reference $reference, array $parameters) : AnnotationMetadata
    {
        $fqcn = $this->determineFqcn($reference);

        $reflection = new ReflectionClass($fqcn);

        $docComment = $reflection->getDocComment();

        $properties = array_map(static function (ReflectionProperty $property) {
            return new AnnotationProperty($property->getName());
        }, $reflection->getProperties(ReflectionProperty::IS_PUBLIC));

        $attributes = [];

        $targets = [];

        return new AnnotationMetadata(
            strpos($docComment, '@Annotation') !== false,
            null,
            $parameters,
            $properties,
            $attributes,
            $targets
        );
    }

    private function determineFqcn(Reference $reference) : string
    {
        if ($reference->isFullyQualified()) {
            return $reference->getIdentifier();
        }

        return $this->aliasesToFqcn[$reference->getIdentifier()];
    }
}
