<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Metadata;

use Doctrine\Annotations\Metadata\Type\BooleanType;
use Doctrine\Annotations\Metadata\Type\FloatType;
use Doctrine\Annotations\Metadata\Type\IntegerType;
use Doctrine\Annotations\Metadata\Type\ListType;
use Doctrine\Annotations\Metadata\Type\MapType;
use Doctrine\Annotations\Metadata\Type\MixedType;
use Doctrine\Annotations\Metadata\Type\StringType;
use Doctrine\Annotations\Metadata\Type\Type;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayTypeNode;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use function assert;
use function count;
use function strcasecmp;
use function strtolower;

final class TypeParser
{
    /** @var Lexer */
    private $lexer;

    /** @var PhpDocParser */
    private $phpDocParser;

    public function __construct(Lexer $lexer, PhpDocParser $phpDocParser)
    {
        $this->lexer        = $lexer;
        $this->phpDocParser = $phpDocParser;
    }

    public function parsePropertyType(string $docBlock, bool $required) : ?Type
    {
        $tags = $this->parse($docBlock)->getVarTagValues();

        assert(count($tags) <= 1);

        if (count($tags) === 0) {
            return new MixedType();
        }

        $typeNode = $tags[0]->type;

        if (! $required) {
            assert(
                $typeNode instanceof NullableTypeNode
                || ($typeNode instanceof UnionTypeNode && count($typeNode->types) === 2)
            );

            if ($typeNode->types[0] instanceof IdentifierTypeNode && strcasecmp($typeNode->types[0]->name, 'null') === 0) {
                return $this->resolveType($typeNode->types[1]);
            }

            if ($typeNode->types[1] instanceof IdentifierTypeNode && strcasecmp($typeNode->types[1]->name, 'null') === 0) {
                return $this->resolveType($typeNode->types[0]);
            }

            assert(false, 'null|null found');
        }

        return $this->resolveType($typeNode);
    }

    private function parse(string $docBlock) : PhpDocNode
    {
        return $this->phpDocParser->parse(new TokenIterator($this->lexer->tokenize($docBlock)));
    }

    private function resolveType(TypeNode $typeNode) : Type
    {
        if ($typeNode instanceof GenericTypeNode) {
            return $this->resolveGenericNode($typeNode);
        }

        if ($typeNode instanceof ArrayTypeNode) {
            return $this->resolveArrayTypeNode($typeNode);
        }

        assert($typeNode instanceof IdentifierTypeNode);

        return $this->resolveScalarNode($typeNode);
    }

    private function resolveGenericNode(GenericTypeNode $typeNode) : Type
    {
        assert(strcasecmp($typeNode->type->name, 'array') === 0);

        if (count($typeNode->genericTypes) === 1) {
            return new ListType($this->resolveType($typeNode->genericTypes[0]));
        }

        if (count($typeNode->genericTypes) === 2) {
            assert($typeNode->genericTypes[0] instanceof IdentifierTypeNode);

            return new MapType(
                $this->resolveScalarNode($typeNode->genericTypes[0]),
                $this->resolveType($typeNode->genericTypes[1])
            );
        }

        assert(false, '>2 generic array args');
    }

    private function resolveArrayTypeNode(ArrayTypeNode $typeNode) : Type
    {
        return new ListType($this->resolveType($typeNode->type));
    }

    private function resolveScalarNode(TypeNode $typeNode) : Type
    {
        assert($typeNode instanceof IdentifierTypeNode);

        switch (strtolower($typeNode->name)) {
            case 'bool':
                return new BooleanType();
            case 'int':
            case 'integer':
                return new IntegerType();
            case 'float':
            case 'double':
            case 'real':
                return new FloatType();
            case 'string':
                return new StringType();
            case 'mixed':
                return new MixedType();
        }

        assert(false, 'invalid type');
    }
}
