<?php

declare(strict_types=1);

namespace Doctrine\Annotations;

use Doctrine\Annotations\Annotation\IgnoreAnnotation;
use Doctrine\Annotations\Assembler\Acceptor\CompositeAcceptor;
use Doctrine\Annotations\Assembler\Acceptor\IgnoredAcceptor;
use Doctrine\Annotations\Assembler\Acceptor\InternalAcceptor;
use Doctrine\Annotations\Assembler\Acceptor\NegatedAcceptor;
use Doctrine\Annotations\Assembler\Assembler;
use Doctrine\Annotations\Constructor\Constructor;
use Doctrine\Annotations\Constructor\Instantiator\ConstructorInstantiatorStrategy;
use Doctrine\Annotations\Constructor\Instantiator\Instantiator;
use Doctrine\Annotations\Constructor\Instantiator\PropertyInstantiatorStrategy;
use Doctrine\Annotations\Metadata\Assembler\AnnotationMetadataAssembler;
use Doctrine\Annotations\Metadata\InternalAnnotations;
use Doctrine\Annotations\Metadata\MetadataCollection;
use Doctrine\Annotations\Metadata\MetadataCollector;
use Doctrine\Annotations\Metadata\Reflection\ClassReflectionProvider;
use Doctrine\Annotations\Metadata\ScopeManufacturer;
use Doctrine\Annotations\Parser\Compiler;
use Doctrine\Annotations\Parser\IgnoredAnnotations;
use Doctrine\Annotations\Parser\Imports;
use Doctrine\Annotations\Parser\Reference\FallbackReferenceResolver;
use Doctrine\Annotations\Parser\Reference\StaticReferenceResolver;
use Doctrine\Annotations\Parser\Scope;
use Doctrine\Annotations\TypeParser\PHPStanTypeParser;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser;
use ReflectionClass;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;
use function assert;

final class NewAnnotationReader implements Reader
{
    /** @var MetadataCollection */
    private $metadataCollection;

    /** @var ClassReflectionProvider */
    private $reflectionProvider;

    /** @var PhpParser */
    private $usesParser;

    /** @var Compiler */
    private $compiler;

    /** @var Constructor */
    private $constructor;

    /** @var AnnotationMetadataAssembler */
    private $metadataAssembler;

    /** @var MetadataCollector */
    private $metadataBuilder;

    /** @var Assembler */
    private $prePublicAssembler;

    /** @var Assembler */
    private $publicAnnotationAssembler;

    public function __construct(
        MetadataCollection $metadataCollection,
        ClassReflectionProvider $reflectionProvider
    ) {
        $this->metadataCollection = $metadataCollection;
        $this->reflectionProvider = $reflectionProvider;
        $this->usesParser         = new PhpParser();
        $this->compiler           = new Compiler();
        $this->constructor        = new Constructor(
            new Instantiator(
                new ConstructorInstantiatorStrategy(),
                new PropertyInstantiatorStrategy()
            )
        );

        $fallbackReferenceResolver = new FallbackReferenceResolver();
        $staticReferenceResolver   = new StaticReferenceResolver();

        $this->metadataAssembler = new AnnotationMetadataAssembler(
            $this->compiler,
            $fallbackReferenceResolver,
            $this->reflectionProvider,
            new PHPStanTypeParser(
                new Lexer(),
                new PhpDocParser(new TypeParser(), new ConstExprParser()),
                $fallbackReferenceResolver
            ),
            new ScopeManufacturer($this->usesParser),
            new Assembler(
                $this->metadataCollection,
                $staticReferenceResolver,
                $this->constructor,
                $this->reflectionProvider,
                new InternalAcceptor($staticReferenceResolver)
            )
        );

        $this->metadataBuilder = new MetadataCollector(
            $this->metadataAssembler,
            new NegatedAcceptor(
                new IgnoredAcceptor($fallbackReferenceResolver)
            ),
            $fallbackReferenceResolver
        );

        $this->prePublicAssembler = new Assembler(
            $this->metadataCollection,
            $staticReferenceResolver,
            $this->constructor,
            $this->reflectionProvider,
            new InternalAcceptor($staticReferenceResolver)
        );

        $this->publicAnnotationAssembler = new Assembler(
            $this->metadataCollection,
            $fallbackReferenceResolver,
            $this->constructor,
            $this->reflectionProvider,
            new CompositeAcceptor(
                new NegatedAcceptor(new IgnoredAcceptor($fallbackReferenceResolver)),
                new NegatedAcceptor(new InternalAcceptor($staticReferenceResolver)))
        );
    }

    /**
     * @return object[]
     */
    public function getClassAnnotations(ReflectionClass $class) : iterable
    {
        yield from $this->collectAnnotations($class);
    }

    public function getClassAnnotation(ReflectionClass $class, $annotationName) : ?object
    {
        return $this->getFirstAnnotationOfType($this->getClassAnnotations($class), $annotationName);
    }

    /**
     * @return object[]
     */
    public function getMethodAnnotations(ReflectionMethod $method) : iterable
    {
        yield from $this->collectAnnotations($method);
    }

    public function getMethodAnnotation(ReflectionMethod $method, $annotationName) : ?object
    {
        return $this->getFirstAnnotationOfType($this->getMethodAnnotations($method), $annotationName);
    }

    /**
     * @return object[]
     */
    public function getPropertyAnnotations(ReflectionProperty $property) : iterable
    {
        yield from $this->collectAnnotations($property);
    }

    public function getPropertyAnnotation(ReflectionProperty $property, $annotationName) : ?object
    {
        return $this->getFirstAnnotationOfType($this->getPropertyAnnotations($property), $annotationName);
    }

    /**
     * @param object[] $annotations
     */
    private function getFirstAnnotationOfType(iterable $annotations, string $desiredType) : ?object
    {
        foreach ($annotations as $annotation) {
            if (! $annotation instanceof $desiredType) {
                continue;
            }

            return $annotation;
        }

        return null;
    }

    /**
     * @return object[]
     */
    private function collectAnnotations(Reflector $subject) : iterable
    {
        assert(
            $subject instanceof ReflectionClass
            || $subject instanceof ReflectionProperty
            || $subject instanceof ReflectionFunctionAbstract
        );

        $docComment = $subject->getDocComment();

        if ($docComment === false) {
            return [];
        }

        $scope = $this->createScope($subject);
        $ast   = $this->compiler->compile($docComment);

        $this->metadataBuilder->collect($ast, $scope, $this->metadataCollection);

        $internalOnPublic = $this->prePublicAssembler->collect(
            $ast,
            new Scope($subject, InternalAnnotations::createImports(), new IgnoredAnnotations())
        );
        foreach ($internalOnPublic as $internalAnnotation) {
            if ($internalAnnotation instanceof IgnoreAnnotation) {
                $scope->getIgnoredAnnotations()->add(...$internalAnnotation->names);
            }
        }

        $scope->getIgnoredAnnotations()->add('IgnoreAnnotation');
        $scope->getIgnoredAnnotations()->add(IgnoreAnnotation::class);

        yield from $this->publicAnnotationAssembler->collect($ast, $scope);
    }

    private function createScope(Reflector $subject) : Scope
    {
        return new Scope(
            $subject,
            $this->collectImports($subject),
            new IgnoredAnnotations(...ImplicitIgnoredAnnotationNames::LIST)
        );
    }

    private function collectImports(Reflector $subject) : Imports
    {
        if ($subject instanceof ReflectionClass) {
            return new Imports($this->usesParser->parseClass($subject));
        }

        if ($subject instanceof ReflectionMethod || $subject instanceof ReflectionProperty) {
            return new Imports($this->usesParser->parseClass($subject->getDeclaringClass()));
        }

        // TODO also support standalone functions
        return new Imports([]);
    }
}
