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
        yield 'simple with no parenthesis' => [
            <<<'DOCBLOCK'
/**
* @Annotation
*/
DOCBLOCK
            ,
            <<<'TRACE'
>  #dockblock
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:simple_identifier, Annotation)

TRACE
            ,
        ];

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
>  >  >  >  token(annot:valued_identifier, Annotation)

TRACE
            ,
        ];

        yield 'multiple without parameters' => [
            <<<'DOCBLOCK'
/** @Annotation1 @Annotation2 @Annotation3 */
DOCBLOCK
            ,
            <<<'TRACE'
>  #dockblock
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:simple_identifier, Annotation1)
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:simple_identifier, Annotation2)
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:simple_identifier, Annotation3)

TRACE
            ,
        ];

        yield 'multiple with comments' => [
            <<<'DOCBLOCK'
/**
 * Hello world
 * @Annotation1
 * Hola mundo
 * @Annotation2
 */
DOCBLOCK
            ,
            <<<'TRACE'
>  #dockblock
>  >  #comments
>  >  >  token(text, Hello world)
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:simple_identifier, Annotation1)
>  >  #comments
>  >  >  token(text, Hola mundo)
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:simple_identifier, Annotation2)

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
>  >  >  >  token(annot:valued_identifier, \Ns\Annotation)
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
>  >  >  >  token(annot:simple_identifier, return)
>  >  #comments
>  >  >  token(text, array<string>)

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
>  >  >  >  token(annot:valued_identifier, \Ns\Name)
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
>  >  >  >  >  >  >  >  >  token(annot:simple_identifier, Annot)
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
>  >  >  >  token(annot:valued_identifier, Annot)
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
>  >  >  >  >  >  >  >  >  >  >  token(annot:simple_identifier, one)
>  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  >  >  token(annot:simple_identifier, two)
>  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  >  >  token(annot:simple_identifier, three)
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
>  >  >  >  >  >  >  >  >  >  >  >  >  token(annot:valued_identifier, one)
>  >  >  >  >  >  >  >  >  >  >  >  >  #values
>  >  >  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:number, 1)
>  >  >  >  >  >  >  >  >  >  #pair
>  >  >  >  >  >  >  >  >  >  >  token(value:identifier, two)
>  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  >  >  >  >  token(annot:valued_identifier, two)
>  >  >  >  >  >  >  >  >  >  >  >  >  #values
>  >  >  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:number, 2)
>  >  >  >  >  >  >  >  >  >  #pair
>  >  >  >  >  >  >  >  >  >  >  token(value:identifier, three)
>  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  >  >  >  >  token(annot:valued_identifier, three)
>  >  >  >  >  >  >  >  >  >  >  >  >  #values
>  >  >  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:number, 3)

TRACE
            ,
        ];

        yield 'ORM Id example' => [
            <<<'DOCBLOCK'
/**
 * @ORM\Id @ORM\Column(type="integer")
 * @ORM\GeneratedValue
 */
DOCBLOCK
            ,
            <<<'TRACE'
>  #dockblock
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:simple_identifier, ORM\Id)
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:valued_identifier, ORM\Column)
>  >  >  >  #values
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, type)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  token(value:string, "integer")
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:simple_identifier, ORM\GeneratedValue)

TRACE
            ,
        ];

        yield 'unicode' => [
            <<<'DOCBLOCK'
/**
 * @Fancy😊Annotation
 */
DOCBLOCK
            ,
            <<<'TRACE'
>  #dockblock
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:simple_identifier, Fancy😊Annotation)

TRACE
            ,
        ];

        yield 'spaces after @' => [
            <<<'DOCBLOCK'
/**
 * @
 * @ Hello world
 */
DOCBLOCK
            ,
            <<<'TRACE'
>  #dockblock
>  >  #comments
>  >  >  token(text, @)
>  >  #comments
>  >  >  token(text, @ Hello world)

TRACE
            ,
        ];

        yield 'ORM Column example' => [
            <<<'DOCBLOCK'
/** @ORM\Column(type="string", length=50, nullable=true) */
DOCBLOCK
                ,
            <<<'TRACE'
>  #dockblock
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:valued_identifier, ORM\Column)
>  >  >  >  #values
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, type)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  token(value:string, "string")
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, length)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  token(value:number, 50)
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, nullable)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  token(value:boolean, true)

TRACE
            ,
        ];

        yield 'complex ORM M:N' => [
            <<<'DOCBLOCK'
/**
 * @ORM\ManyToMany(targetEntity=CmsGroup::class, inversedBy="users", cascade={"persist"})
 * @ORM\JoinTable(name="cms_users_groups",
 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
 *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
 * )
 */
DOCBLOCK
            ,
            <<<'TRACE'
>  #dockblock
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:valued_identifier, ORM\ManyToMany)
>  >  >  >  #values
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, targetEntity)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  #constant
>  >  >  >  >  >  >  >  >  token(value:identifier, CmsGroup)
>  >  >  >  >  >  >  >  >  token(value:colon, :)
>  >  >  >  >  >  >  >  >  token(value:colon, :)
>  >  >  >  >  >  >  >  >  token(value:identifier, class)
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, inversedBy)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  token(value:string, "users")
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, cascade)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  #list
>  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  token(value:string, "persist")
>  >  #annotations
>  >  >  #annotation
>  >  >  >  token(annot:valued_identifier, ORM\JoinTable)
>  >  >  >  #values
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, name)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  token(value:string, "cms_users_groups")
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, joinColumns)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  #list
>  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  >  >  token(annot:valued_identifier, ORM\JoinColumn)
>  >  >  >  >  >  >  >  >  >  >  #values
>  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  #pair
>  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:identifier, name)
>  >  >  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:string, "user_id")
>  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  #pair
>  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:identifier, referencedColumnName)
>  >  >  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:string, "id")
>  >  >  >  >  #value
>  >  >  >  >  >  #pair
>  >  >  >  >  >  >  token(value:identifier, inverseJoinColumns)
>  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  #list
>  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  #annotation
>  >  >  >  >  >  >  >  >  >  >  token(annot:valued_identifier, ORM\JoinColumn)
>  >  >  >  >  >  >  >  >  >  >  #values
>  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  #pair
>  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:identifier, name)
>  >  >  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:string, "group_id")
>  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  #pair
>  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:identifier, referencedColumnName)
>  >  >  >  >  >  >  >  >  >  >  >  >  >  #value
>  >  >  >  >  >  >  >  >  >  >  >  >  >  >  token(value:string, "id")

TRACE
            ,
        ];
    }
}
