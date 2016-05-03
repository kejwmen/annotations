<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Parser;

use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Visitor\Dump;
use Hoa\File\Read;
use PHPUnit\Framework\TestCase;

final class GrammarTest extends TestCase
{
    /**
     * @dataProvider docBlocksProvider()
     */
    public function testGrammar(string $docBlock, string $expectedTrace) : void
    {
        $dumper   = new Dump();
        $compiler = Llk::load(new Read(__DIR__ . '/../../../../../lib/Doctrine/Annotations/Parser/grammar.pp'));

        $ast   = $compiler->parse($docBlock);
        $trace = $dumper->visit($ast);

        self::assertSame($expectedTrace, $trace);
    }

    /**
     * @return string[][]
     */
    public function docBlocksProvider() : iterable
    {
        yield 'simple with empty parenthesis' => [
            <<<'DOCBLOCK'
/**
* @Annotation()
*/
DOCBLOCK
            ,
            <<<'TRACE'
>  #dockblock
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:identifier, Annotation)

TRACE
            ,
        ];

        yield 'fully qualified with parameter' => [
            <<<'DOCBLOCK'
/**
* @\Ns\Annotation("value")
*/
DOCBLOCK
            ,
            <<<'TRACE'
>  #dockblock
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:identifier, \Ns\Annotation)
>  >  >  >  #values
>  >  >  >  >  #value
>  >  >  >  >  >  token(value:string, "value")

TRACE
            ,
        ];

        yield 'with array' => [
            <<<'DOCBLOCK'
/**
* @return array<string>
*/
DOCBLOCK
            ,
            <<<'TRACE'
>  #dockblock
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:identifier, return)
>  >  #comments
>  >  >  token(values:text, array<string>)

TRACE
            ,
        ];

        yield 'fully qualified, nested, multiple parameters' =>  [
            <<<'DOCBLOCK'
/**
* @\Ns\Name(int=1, annot=@Annot, float=1.2)
*/
DOCBLOCK
            ,
            <<<'TRACE'
>  #dockblock
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:identifier, \Ns\Name)
>  >  >  >  #values
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, int)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  token(value:number, 1)
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, annot)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  token(annot:identifier, Annot)
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, float)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  token(value:number, 1.2)

TRACE
            ,
        ];

        yield 'nested, with arrays' => [
            <<<'DOCBLOCK'
/**
* @Annot(
*  v1={1,2,3},
*  v2={@one,@two,@three},
*  v3={one=1,two=2,three=3},
*  v4={one=@one(1),two=@two(2),three=@three(3)}
* )
*/
DOCBLOCK
            ,
            <<<'TRACE'
>  #dockblock
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:identifier, Annot)
>  >  >  >  #values
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, v1)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  #list
>  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  token(value:number, 1)
>  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  token(value:number, 2)
>  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  token(value:number, 3)
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, v2)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  #list
>  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  >  >  token(annot:identifier, one)
>  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  >  >  token(annot:identifier, two)
>  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  >  >  token(annot:identifier, three)
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, v3)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  #map
>  >  >  >  >  >  >  >  >  #pairs
>  >  >  >  >  >  >  >  >  >  #pair
>  >  >  >  >  >  >  >  >  >  >  token(value:identifier, one)
>  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  token(value:number, 1)
>  >  >  >  >  >  >  >  >  >  #pair
>  >  >  >  >  >  >  >  >  >  >  token(value:identifier, two)
>  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  token(value:number, 2)
>  >  >  >  >  >  >  >  >  >  #pair
>  >  >  >  >  >  >  >  >  >  >  token(value:identifier, three)
>  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  token(value:number, 3)
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, v4)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  #map
>  >  >  >  >  >  >  >  >  #pairs
>  >  >  >  >  >  >  >  >  >  #pair
>  >  >  >  >  >  >  >  >  >  >  token(value:identifier, one)
>  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  >  >  >  >  token(annot:identifier, one)
>  >  >  >  >  >  >  >  >  >  >  >  >  #values
>  >  >  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:number, 1)
>  >  >  >  >  >  >  >  >  >  #pair
>  >  >  >  >  >  >  >  >  >  >  token(value:identifier, two)
>  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  >  >  >  >  token(annot:identifier, two)
>  >  >  >  >  >  >  >  >  >  >  >  >  #values
>  >  >  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:number, 2)
>  >  >  >  >  >  >  >  >  >  #pair
>  >  >  >  >  >  >  >  >  >  >  token(value:identifier, three)
>  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  >  >  >  >  token(annot:identifier, three)
>  >  >  >  >  >  >  >  >  >  >  >  >  #values
>  >  >  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:number, 3)

TRACE
            ,
        ];
    }
}
