<?php

namespace Doctrine\Tests\Annotations\Fixtures;

/**
 * @Annotation
 * @Target("ALL")
 */
final class AnnotationWithVarType
{

    /**
     * @var mixed
     */
    public $mixed;

    /**
     * @var boolean
     */
    public $boolean;

    /**
     * @var bool
     */
    public $bool;

    /**
     * @var float
     */
    public $float;

    /**
     * @var string
     */
    public $string;

    /**
     * @var integer
     */
    public $integer;

    /**
     * @var array
     */
    public $array;

    /**
     * @var array<string,mixed>
     */
    public $arrayMap;

    /**
     * @var \Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAll
     */
    public $annotation;

    /**
     * @var array<integer>
     */
    public $arrayOfIntegers;

    /**
     * @var string[]
     */
    public $arrayOfStrings;

    /**
     * @var \Doctrine\Tests\Annotations\Fixtures\AnnotationTargetAll[]
     */
    public $arrayOfAnnotations;

}
