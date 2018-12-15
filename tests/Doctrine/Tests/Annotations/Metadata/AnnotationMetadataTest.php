<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata;

use Doctrine\Annotations\Metadata\PropertyMetadata;
use Doctrine\Annotations\Metadata\Type\MixedType;
use PHPUnit\Framework\TestCase;

final class AnnotationMetadataTest extends TestCase
{
    public function testDeterminesMarkedDefaultProperty() : void
    {
        $defaultProperty = new PropertyMetadata('bar', new MixedType(), [], false, true);

        $metadata = AnnotationMetadataMother::withProperties(
            new PropertyMetadata('foo', new MixedType()),
            $defaultProperty
        );

        $result = $metadata->getDefaultProperty();

        $this->assertSame($defaultProperty, $result);
    }

    public function testDeterminesMarkedDefaultPropertyForMultipleMarkedPropertiesAndThrows() : void
    {
        $defaultProperty = new PropertyMetadata('bar', new MixedType(), [], false, true);
        $anotherDefaultProperty = new PropertyMetadata('baz', new MixedType(), [], false, true);

        $this->expectException(\InvalidArgumentException::class);

        AnnotationMetadataMother::withProperties(
            new PropertyMetadata('foo', new MixedType()),
            $defaultProperty,
            $anotherDefaultProperty
        );
    }

    public function testDeterminesDefaultPropertyFallingBackToFirstProperty() : void
    {
        $firstPropertyMetadata = new PropertyMetadata('foo', new MixedType());

        $metadata = AnnotationMetadataMother::withProperties(
            $firstPropertyMetadata,
            new PropertyMetadata('bar', new MixedType())
        );

        $result = $metadata->getDefaultProperty();

        $this->assertSame($firstPropertyMetadata, $result);
    }

    public function testDeterminesNoDefaultPropertyForNoProperties() : void
    {
        $metadata = AnnotationMetadataMother::withProperties();

        $result = $metadata->getDefaultProperty();

        $this->assertNull($result);
    }
}
