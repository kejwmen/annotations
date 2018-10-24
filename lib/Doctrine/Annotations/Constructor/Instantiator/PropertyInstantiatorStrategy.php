<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Constructor\Instantiator;

use Doctrine\Annotations\Constructor\PropertyPopulator;
use Doctrine\Annotations\Metadata\AnnotationMetadata;

final class PropertyInstantiatorStrategy implements InstantiatorStrategy
{
    /** @var PropertyPopulator */
    private $populator;

    public function __construct(PropertyPopulator $populator)
    {
        $this->populator = $populator;
    }

    /**
     * @param mixed[] $parameters array<string, mixed>
     */
    public function construct(AnnotationMetadata $metadata, array $parameters) : object
    {
        $class      = $metadata->getName();
        $annotation = new $class();

        $this->populator->populate($annotation, $parameters);

        return $annotation;
    }
}
