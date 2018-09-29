<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Metadata;

use Doctrine\Annotations\TypeParser\PHPStanTypeParser;
use Doctrine\Annotations\TypeParser\TypeParser;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TypeParser as BaseTypeParser;
use PHPUnit\Framework\TestCase;

final class TypeParserTest extends TestCase
{
    /** @var TypeParser */
    private $parser;

    protected function setUp() : void
    {
        $this->parser = new PHPStanTypeParser(
            new Lexer(),
            new PhpDocParser(new BaseTypeParser(), new ConstExprParser())
        );
    }

    /**
     * @dataProvider parserProvider()
     */
    public function testParser(string $docBlock, bool $required, string $expectedType) : void
    {
        self::assertSame(
            $expectedType,
            $this->parser->parsePropertyType($docBlock, $required)->describe()
        );
    }

    /**
     * @return string[][]
     */
    public function parserProvider() : iterable
    {
        yield 'mixed' => [
            '/** @var mixed */',
            true,
            'mixed',
        ];
        yield 'nullable mixed' => [
            '/** @var mixed|null */',
            false,
            'mixed',
        ];
        yield 'ínteger' => [
            '/** @var int */',
            true,
            'integer',
        ];
        yield 'nullable ínteger' => [
            '/** @var int|null */',
            false,
            'integer',
        ];
        yield 'nullable ínteger reverse' => [
            '/** @var null|int */',
            false,
            'integer',
        ];
        yield 'list of integers' => [
            '/** @var array<int> */',
            true,
            'array<integer>',
        ];
        yield 'nullable list of integers' => [
            '/** @var array<int>|null */',
            false,
            'array<integer>',
        ];
        yield 'list of integers (alternative form)' => [
            '/** @var int[] */',
            true,
            'array<integer>',
        ];
        yield 'map of integers to strings' => [
            '/** @var array<int, string> */',
            true,
            'array<integer, string>',
        ];
        yield 'nullable map of integers to strings' => [
            '/** @var array<int, string>|null */',
            false,
            'array<integer, string>',
        ];
    }
}
