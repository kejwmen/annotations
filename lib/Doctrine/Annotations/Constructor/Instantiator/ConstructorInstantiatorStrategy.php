<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Constructor\Instantiator;

use Doctrine\Annotations\Constructor\PropertyPopulator;
use Doctrine\Annotations\Metadata\AnnotationMetadata;
use Doctrine\Annotations\Metadata\PropertyMetadata;
use function array_key_exists;
use function array_map;
use function assert;
use function in_array;

final class ConstructorInstantiatorStrategy implements InstantiatorStrategy
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
        $propertyParametersNames = array_map(static function (PropertyMetadata $propertyMetadata) {
            return $propertyMetadata->getName();
        }, $metadata->getProperties());

        $propertyParameters    = [];
        $constructorParameters = [];

        foreach ($parameters as $name => $value) {
            if (in_array($name, $propertyParametersNames, true)) {
                $propertyParameters[$name] = $value;

                continue;
            }

            $constructorParameters[$name] = $value;
        }

        if (array_key_exists(null, $constructorParameters)) {
            $defaultProperty = $metadata->getDefaultProperty();
            assert($defaultProperty !== null);

            $constructorParameters[$defaultProperty->getName()] = $constructorParameters[null];
            unset($constructorParameters[null]);
        }

        $class = $metadata->getName();

        $annotation = new $class($constructorParameters);

        $this->populator->populate($annotation, $propertyParameters);

        return $annotation;
    }
}
