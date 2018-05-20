<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser;

use Doctrine\Annotations\Parser\Ast\Annotations;
use Doctrine\Annotations\Parser\Visitor\Raw\AstBuilder;
use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Llk\Parser;
use Hoa\File\Read;

/**
 * @internal
 */
final class Compiler
{
    private const ROOT_NODE = 'docblock';

    /** @var Parser */
    private $parser;

    /** @var AstBuilder */
    private $visitor;

    public function __construct()
    {
        $this->parser  = Llk::load(new Read(__DIR__ . '/grammar.pp'));
        $this->visitor = new AstBuilder();
    }

    public function compile(string $docblock) : Annotations
    {
        $tree = $this->parser->parse($docblock, self::ROOT_NODE);

        return $this->visitor->visit($tree);
    }
}
