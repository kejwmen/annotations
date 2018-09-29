<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Constructor\Instantiator;

use Doctrine\Annotations\Metadata\AnnotationMetadata;
use function array_key_exists;

final class ConstructorInstantiatorStrategy implements InstantiatorStrategy
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function construct(AnnotationMetadata $metadata, array $parameters) : object
    {
        if (array_key_exists(null, $parameters)) {
            $defaultProperty = $metadata->getDefaultProperty();
            assert($defaultProperty !== null);

            $parameters['value'] = $parameters[null];
            unset($parameters[null]);
        }

        $class = $metadata->getName();
        return new $class($parameters);
    }
}
