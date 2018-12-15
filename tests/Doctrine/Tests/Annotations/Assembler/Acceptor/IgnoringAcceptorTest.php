<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Assembler\Acceptor;

use Doctrine\Annotations\Assembler\Acceptor\IgnoringAcceptor;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Tests\Annotations\Parser\Reference\IdentifierPassingReferenceResolver;
use Doctrine\Tests\Annotations\Parser\Reference\NotResolvableReferenceResolver;
use Doctrine\Tests\Annotations\Parser\ScopeMother;
use PHPUnit\Framework\TestCase;

final class IgnoringAcceptorTest extends TestCase
{
    public function testAcceptsNotResolvedReference() : void
    {
        $reference = new Reference('Foo', false);
        $scope     = ScopeMother::example();
        $acceptor  = new IgnoringAcceptor(new NotResolvableReferenceResolver());

        $result = $acceptor->accepts($reference, $scope);

        $this->assertTrue($result);
    }

    public function testAcceptsIgnoredAnnotation() : void
    {
        $reference = new Reference('Foo', false);
        $scope     = ScopeMother::withIgnoredAnnotations(['bar', 'Foo']);
        $acceptor  = new IgnoringAcceptor(new IdentifierPassingReferenceResolver());

        $result = $acceptor->accepts($reference, $scope);

        $this->assertTrue($result);
    }

    public function testNotAcceptsNotIgnoredAnnotation() : void
    {
        $reference = new Reference('Foo', false);
        $scope     = ScopeMother::withIgnoredAnnotations(['bar']);
        $acceptor  = new IgnoringAcceptor(new IdentifierPassingReferenceResolver());

        $result = $acceptor->accepts($reference, $scope);

        $this->assertFalse($result);
    }
}
