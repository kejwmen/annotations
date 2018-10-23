<?php

declare(strict_types=1);

namespace Doctrine\Tests\Annotations\Fixtures;

/**
 * @AnnotationWithVarType(
 *     mixed=null,
 *     boolean=true,
 *     bool=false,
 *     float=3.14,
 *     string="foo",
 *     integer=42,
 *     array={"foo", 42, false},
 *     arrayMap={"foo": "bar"},
 *     annotation=@AnnotationTargetAll(name="baz"),
 *     arrayOfIntegers={1,2,3},
 *     arrayOfStrings={"foo","bar","baz"},
 *     arrayOfAnnotations={
 *         @AnnotationTargetAll,
 *         @AnnotationTargetAll(name=123)
 *     }
 * )
 */
final class ClassWithFullValidUsageOfAnnotationWithVarType
{
}
