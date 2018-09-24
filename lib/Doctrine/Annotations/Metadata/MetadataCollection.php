<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

use ArrayAccess;
use function assert;

final class MetadataCollection implements ArrayAccess
{
    /** @var AnnotationMetadata[] */
    private $metadata = [];

    public function __construct(AnnotationMetadata ...$metadatas)
    {
        foreach ($metadatas as $metadata) {
            $this->add($metadata);
        }
    }

    public function add(AnnotationMetadata $metadata) : void
    {
        assert(! isset($this[$metadata->getName()]));

        $this->metadata[$metadata->getName()] = $metadata;
    }

    /**
     * @param string $name
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    public function offsetGet($name) : AnnotationMetadata
    {
        assert(isset($this[$name]));

        return $this->metadata[$name];
    }

    /**
     * @param null               $name
     * @param AnnotationMetadata $metadata
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    public function offsetSet($name, $metadata) : void
    {
        assert($name === null);

        $this->add($metadata);
    }

    /**
     * @param string $name
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    public function offsetExists($name) : bool
    {
        return isset($this->metadata[$name]);
    }

    /**
     * @param string $name
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    public function offsetUnset($name) : void
    {
        assert(false, 'immutable');
    }
}