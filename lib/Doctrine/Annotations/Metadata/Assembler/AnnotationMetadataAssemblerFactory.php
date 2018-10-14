<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata\Assembler;

use Doctrine\Annotations\Assembler\Acceptor\InternalAcceptor;
use Doctrine\Annotations\Assembler\Assembler;
use Doctrine\Annotations\Constructor\Constructor;
use Doctrine\Annotations\Constructor\Instantiator\ConstructorInstantiatorStrategy;
use Doctrine\Annotations\Constructor\Instantiator\Instantiator;
use Doctrine\Annotations\Constructor\Instantiator\PropertyInstantiatorStrategy;
use Doctrine\Annotations\Metadata\InternalAnnotations;
use Doctrine\Annotations\Metadata\Reflection\DefaultReflectionProvider;
use Doctrine\Annotations\Metadata\ScopeManufacturer;
use Doctrine\Annotations\Parser\Compiler;
use Doctrine\Annotations\Parser\Reference\FallbackReferenceResolver;
use Doctrine\Annotations\Parser\Reference\StaticReferenceResolver;
use Doctrine\Annotations\PhpParser;
use Doctrine\Annotations\TypeParser\PHPStanTypeParserFactory;

final class AnnotationMetadataAssemblerFactory
{
    public function build(): AnnotationMetadataAssembler
    {
        return new AnnotationMetadataAssembler(
            new Compiler(),
            new FallbackReferenceResolver(),
            new DefaultReflectionProvider(),
            (new PHPStanTypeParserFactory())->build(),
            new ScopeManufacturer(new PhpParser()),
            new Assembler(
                InternalAnnotations::createMetadata(),
                new StaticReferenceResolver(),
                new Constructor(
                    new Instantiator(
                        new ConstructorInstantiatorStrategy(),
                        new PropertyInstantiatorStrategy()
                    )
                ),
                new DefaultReflectionProvider(),
                new InternalAcceptor(
                    new StaticReferenceResolver()
                )
            )
        );
    }
}
