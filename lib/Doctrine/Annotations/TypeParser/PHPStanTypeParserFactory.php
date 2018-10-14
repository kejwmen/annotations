<?php

declare(strict_types=1);

namespace Doctrine\Annotations\TypeParser;

use Doctrine\Annotations\Parser\Reference\FallbackReferenceResolver;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser;

final class PHPStanTypeParserFactory
{
    public function build(): PHPStanTypeParser
    {
        $internatlTypeParser = new \PHPStan\PhpDocParser\Parser\TypeParser();

        return new PHPStanTypeParser(
            new Lexer(),
            new PhpDocParser($internatlTypeParser, new ConstExprParser()),
            $internatlTypeParser,
            new FallbackReferenceResolver()
        );
    }
}
