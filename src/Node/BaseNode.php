<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Node
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Node;

/**
 * @package    Gems
 * @subpackage Hl7\Node
 * @since      Class available since version 1.0
 */
abstract class BaseNode implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * @var array<BaseNode>
     */
    protected array $children = [];

    protected string $escapeSequenceKey;

    protected BaseNode|null $parent = null;

    public function __toString()
    {
        $key = $this->getMessage()?->getEscapeSequence($this->escapeSequenceKey);
        return implode($key ?? '', $this->children);
    }

    public function append(BaseNode $node)
    {
        $node->setParent($this);
        array_push($this->children, $node);
    }

    public function count(): int
    {
        return count($this->children);
    }

    /*
     * (non-PHPdoc)
     * @see Iterator::current()
     */
    public function current()
    {
        return current($this->children);
    }

    public function getMessage(): ?Message
    {
        if($this->parent instanceof Message) {
            return $this->parent;
        }
        return $this->parent?->getMessage();
    }

    public function getRootNode(): BaseNode
    {
        if(is_null($this->parent)) {
            return $this;
        }
        return $this->parent->getRootNode();
    }

    /*
     * (non-PHPdoc)
     * @see Iterator::key()
     */
    public function key()
    {
        return key($this->children);
    }

    /*
     * (non-PHPdoc)
     * @see Iterator::next()
     */
    public function next()
    {
        return next($this->children);
    }

    /*
     * (non-PHPdoc)
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->children);
    }

    /*
     * (non-PHPdoc)
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return $this->children[$offset];
    }

    /*
     * (non-PHPdoc)
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        $this->children[$offset] = $value;
    }

    /*
     * (non-PHPdoc)
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->children[$offset]);
    }

    /*
     * (non-PHPdoc)
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        return reset($this->children);
    }

    public function setParent(BaseNode $node): void
    {
        $this->parent = $node;
    }

    /*
     * (non-PHPdoc)
     * @see Iterator::valid()
     */
    public function valid()
    {
        return isset($this->children[$this->key()]);
    }
}