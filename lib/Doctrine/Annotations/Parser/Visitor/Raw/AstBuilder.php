<?php

declare(strict_types=1);

namespace Doctrine\Annotations\Parser\Visitor\Raw;

use Doctrine\Annotations\Parser\Ast\Annotation;
use Doctrine\Annotations\Parser\Ast\Annotations;
use Doctrine\Annotations\Parser\Ast\Collection\ListCollection;
use Doctrine\Annotations\Parser\Ast\Collection\MapCollection;
use Doctrine\Annotations\Parser\Ast\ConstantFetch;
use Doctrine\Annotations\Parser\Ast\Node;
use Doctrine\Annotations\Parser\Ast\Pair;
use Doctrine\Annotations\Parser\Ast\Parameter\NamedParameter;
use Doctrine\Annotations\Parser\Ast\Parameter\UnnamedParameter;
use Doctrine\Annotations\Parser\Ast\Parameters;
use Doctrine\Annotations\Parser\Ast\Reference;
use Doctrine\Annotations\Parser\Ast\Scalar\BooleanScalar;
use Doctrine\Annotations\Parser\Ast\Scalar\FloatScalar;
use Doctrine\Annotations\Parser\Ast\Scalar\Identifier;
use Doctrine\Annotations\Parser\Ast\Scalar\IntegerScalar;
use Doctrine\Annotations\Parser\Ast\Scalar\NullScalar;
use Doctrine\Annotations\Parser\Ast\Scalar\StringScalar;
use Hoa\Compiler\Llk\TreeNode;
use Hoa\Visitor\Element;
use Hoa\Visitor\Visit;
use function assert;
use function ltrim;
use function sprintf;
use function str_replace;
use function strcasecmp;

final class AstBuilder implements Visit
{
    /**
     * @param int|null $handle
     * @param int|null $eldnah
     *
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingParameterTypeHint
     */
    public function visit(Element $node, &$handle = null, $eldnah = null) : Node
    {
        assert($node instanceof TreeNode);

        if ($node->isToken()) {
            return $this->visitToken($node);
        }

        return $this->visitNode($node, $handle, $eldnah);
    }

    private function visitNode(TreeNode $node, ?int &$handle, ?int $eldnah) : Node
    {
        switch ($node->getId()) {
            case '#docblock':
                return $this->visitAnnotations($node, $handle, $eldnah);
            case '#annotation':
                return $this->visitAnnotation($node, $handle, $eldnah);
            case '#pair':
                return $this->visitPair($node, $handle, $eldnah);
            case '#parameters':
                return $this->visitParameters($node, $handle, $eldnah);
            case '#named_parameter':
                return $this->visitNamedParameter($node, $handle, $eldnah);
            case '#unnamed_parameter':
                return $this->visitUnnamedParameter($node, $handle, $eldnah);
            case '#value':
                return $this->visitValue($node, $handle, $eldnah);
            case '#map':
                return $this->visitMap($node, $handle, $eldnah);
            case '#list':
                return $this->visitList($node, $handle, $eldnah);
            case '#constant':
                return $this->visitConstant($node, $handle, $eldnah);
            case '#reference':
                return $this->visitReference($node);
            case '#string':
                return $this->visitString($node);
        }

        assert(false, sprintf('Unsupported node %s.', $node->getId()));
    }

    private function visitToken(TreeNode $node) : Node
    {
        $value = $node->getValueValue();

        switch ($node->getValueToken()) {
            case 'identifier':
                return new Identifier($value);
            case 'null':
                return new NullScalar();
            case 'boolean':
                return new BooleanScalar(strcasecmp($value, 'true') === 0);
            case 'integer':
                $intValue = (int) $value;
                assert((string) $intValue === $value, 'Integer overflow');

                return new IntegerScalar($intValue);
            case 'float':
                return new FloatScalar((float) $value);
        }

        assert(false, sprintf('Unsupported token %s.', $node->getValueToken()));
    }

    private function visitAnnotations(TreeNode $node, ?int &$handle, ?int $eldnah) : Annotations
    {
        return new Annotations(
            ...(function (TreeNode ...$nodes) use (&$handle, $eldnah) : iterable {
                foreach ($nodes as $node) {
                    yield $node->accept($this, $handle, $eldnah);
                }
            })(...$node->getChildren())
        );
    }

    private function visitAnnotation(TreeNode $node, ?int &$handle, ?int $eldnah) : Annotation
    {
        $identifier = $node->getChild(0)->getValueValue();

        return new Annotation(
            new Reference(ltrim($identifier, '\\'), $identifier[0] === '\\'),
            $node->childExists(1) ? $node->getChild(1)->accept($this, $handle, $eldnah) : new Parameters()
        );
    }

    private function visitPair(TreeNode $node, ?int &$handle, ?int $eldnah) : Pair
    {
        return new Pair(
            $node->getChild(0)->accept($this, $handle, $eldnah),
            $node->getChild(1)->accept($this, $handle, $eldnah)
        );
    }

    private function visitParameters(TreeNode $node, ?int &$handle, ?int $eldnah) : Parameters
    {
        return new Parameters(
            ...(function (TreeNode ...$nodes) use (&$handle, $eldnah) : iterable {
                foreach ($nodes as $node) {
                    yield $node->accept($this, $handle, $eldnah);
                }
            })(...$node->getChildren())
        );
    }

    private function visitNamedParameter(TreeNode $node, ?int &$handle, ?int $eldnah) : NamedParameter
    {
        return new NamedParameter(
            $node->getChild(0)->accept($this, $handle, $eldnah),
            $node->getChild(1)->accept($this, $handle, $eldnah)
        );
    }

    private function visitUnnamedParameter(TreeNode $node, ?int &$handle, ?int $eldnah) : UnnamedParameter
    {
        return new UnnamedParameter($node->getChild(0)->accept($this, $handle, $eldnah));
    }

    private function visitValue(TreeNode $node, ?int &$handle, ?int $eldnah) : Node
    {
        return $node->getChild(0)->accept($this, $handle, $eldnah);
    }

    private function visitMap(TreeNode $node, ?int &$handle, ?int $eldnah) : MapCollection
    {
        return new MapCollection(
            ...(function (TreeNode ...$nodes) use (&$handle, $eldnah) : iterable {
                foreach ($nodes as $node) {
                    yield $node->accept($this, $handle, $eldnah);
                }
            })(...$node->getChildren())
        );
    }

    private function visitList(TreeNode $node, ?int &$handle, ?int $eldnah) : ListCollection
    {
        return new ListCollection(
            ...(function (TreeNode ...$nodes) use (&$handle, $eldnah) : iterable {
                foreach ($nodes as $node) {
                    yield $node->accept($this, $handle, $eldnah);
                }
            })(...$node->getChildren())
        );
    }

    private function visitConstant(TreeNode $node, ?int &$handle, ?int $eldnah) : ConstantFetch
    {
        return new ConstantFetch(
            $node->getChild(0)->accept($this, $handle, $eldnah),
            $node->getChild(1)->accept($this, $handle, $eldnah)
        );
    }

    private function visitReference(TreeNode $node) : Reference
    {
        $child = $node->getChild(0);
        $value = $child->getValueValue();

        if ($child->getValueToken() === 'identifier_ns' && $value[0] === '\\') {
            return new Reference(ltrim($value, '\\'), true);
        }

        return new Reference($value, false);
    }

    private function visitString(TreeNode $node) : StringScalar
    {
        return new StringScalar(str_replace('\\\\', '\\', $node->getChild(0)->getValueValue()));
    }
}
