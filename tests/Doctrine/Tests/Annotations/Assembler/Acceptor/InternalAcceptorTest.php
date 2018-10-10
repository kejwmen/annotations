<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Assembler\Acceptor;

use Doctrine\Annotations\Assembler\Acceptor\InternalAcceptor;
use Doctrine\Annotations\Metadata\InternalAnnotations;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Tests\Annotations\Annotation\Parser\Reference\IdentifierPassingReferenceResolver;
use Doctrine\Tests\Annotations\Annotation\Parser\Reference\NotResolvableReferenceResolver;
use Doctrine\Tests\Annotations\Annotation\Parser\ScopeMother;
use PHPUnit\Framework\TestCase;

class InternalAcceptorTest extends TestCase
{
    public function testNotAcceptsNotResolvedReference() : void
    {
        $reference = new Reference('Foo', false);
        $scope     = ScopeMother::example();
        $acceptor  = new InternalAcceptor(new NotResolvableReferenceResolver());

        $result = $acceptor->accepts($reference, $scope);

        $this->assertFalse($result);
    }

    /**
     * @dataProvider internalAnnotationsProvider
     */
    public function testAcceptsInternalAnnotations(string $name) : void
    {
        $reference = new Reference($name, true);
        $scope     = ScopeMother::example();
        $acceptor  = new InternalAcceptor(new IdentifierPassingReferenceResolver());

        $result = $acceptor->accepts($reference, $scope);

        $this->assertTrue($result);
    }

    public function testNotAcceptsNonInternalAnnotations() : void
    {
        $reference = new Reference('Foo', false);
        $scope     = ScopeMother::example();
        $acceptor  = new InternalAcceptor(new IdentifierPassingReferenceResolver());

        $result = $acceptor->accepts($reference, $scope);

        $this->assertFalse($result);
    }

    public function internalAnnotationsProvider() : iterable
    {
        foreach (InternalAnnotations::getNames() as $name) {
            yield [$name];
        }
    }
}
