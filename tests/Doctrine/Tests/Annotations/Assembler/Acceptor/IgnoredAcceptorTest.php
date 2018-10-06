<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Assembler\Acceptor;

use Doctrine\Annotations\Assembler\Acceptor\IgnoredAcceptor;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Tests\Annotations\Annotation\Parser\Reference\NotResolvableReferenceResolver;
use Doctrine\Tests\Annotations\Annotation\Parser\Reference\IdentifierPassingReferenceResolver;
use Doctrine\Tests\Annotations\Annotation\Parser\ScopeMother;
use PHPUnit\Framework\TestCase;

final class IgnoredAcceptorTest extends TestCase
{
    public function testAcceptsNotResolvedReference()
    {
        $reference = new Reference('Foo', false);
        $scope = ScopeMother::example();
        $acceptor = new IgnoredAcceptor(new NotResolvableReferenceResolver());

        $result = $acceptor->accepts($reference, $scope);

        $this->assertTrue($result);
    }

    public function testAcceptsIgnoredAnnotation()
    {
        $reference = new Reference('Foo', false);
        $scope = ScopeMother::withIgnoredAnnotations(['bar', 'Foo']);
        $acceptor = new IgnoredAcceptor(new IdentifierPassingReferenceResolver());

        $result = $acceptor->accepts($reference, $scope);

        $this->assertTrue($result);
    }

    public function testNotAcceptsNotIgnoredAnnotation()
    {
        $reference = new Reference('Foo', false);
        $scope = ScopeMother::withIgnoredAnnotations(['bar']);
        $acceptor = new IgnoredAcceptor(new IdentifierPassingReferenceResolver());

        $result = $acceptor->accepts($reference, $scope);

        $this->assertFalse($result);
    }
}
