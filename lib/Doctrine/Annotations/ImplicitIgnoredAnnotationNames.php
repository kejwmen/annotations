<?php

declare(strict_types=1);

namespace Doctrine\Annotations;

/**
 *  A list with annotations that are not causing exceptions when not resolved to an annotation class.
 *
 *  The names are case sensitive.
 */
final class ImplicitIgnoredAnnotationNames
{
    private const SLOT_SIZE = 100;

    private const ReservedAnnotations = [];

    private const WidelyUsedNonStandard = [
        self::SLOT_SIZE => 'fix' ,
        'fixme',
        'override',
    ];

    private const PHPDocumentor1 = [
        2 * self::SLOT_SIZE => 'abstract',
        'access',
        'code',
        'deprec',
        'endcode',
        'exception',
        'final',
        'ingroup',
        'inheritdoc',
        'inheritDoc',
        'magic',
        'name',
        'toc',
        'tutorial',
        'private',
        'static',
        'staticvar',
        'staticVar',
        'throw',
    ];

    private const PHPDocumentor2 = [
        3 * self::SLOT_SIZE => 'api',
        'author',
        'category',
        'copyright',
        'deprecated',
        'example',
        'filesource',
        'global',
        'ignore',
        /* Can we enable this? 'index', */
        'internal',
        'license',
        'link',
        'method',
        'package',
        'param',
        'property',
        'property-read',
        'property-write',
        'return',
        'see',
        'since',
        'source',
        'subpackage',
        'throws',
        'todo',
        'TODO',
        'usedby',
        'uses',
        'var',
        'version',
    ];

    private const PHPUnit = [
        4 * self::SLOT_SIZE => 'codeCoverageIgnore',
        'codeCoverageIgnoreStart',
        'codeCoverageIgnoreEnd',
    ];

    private const PHPCheckStyle = [
        5 * self::SLOT_SIZE => 'SuppressWarnings',
    ];

    private const PhpStorm = [
        6 * self::SLOT_SIZE => 'noinspection',
    ];

    private const PEAR = [
        7 * self::SLOT_SIZE => 'package_version',
    ];

    private const PlainUML = [
        8 * self::SLOT_SIZE => 'startuml',
        'enduml',
    ];

    public const LIST = self::ReservedAnnotations
        + self::WidelyUsedNonStandard
        + self::PHPDocumentor1
        + self::PHPDocumentor2
        + self::PHPUnit
        + self::PHPCheckStyle
        + self::PhpStorm
        + self::PEAR
        + self::PlainUML;
}
