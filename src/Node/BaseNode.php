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

    public function current(): mixed
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

    public function key(): mixed
    {
        return key($this->children);
    }

    public function next(): void
    {
         next($this->children);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->children);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->children[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->children[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->children[$offset]);
    }

    public function rewind(): void
    {
        reset($this->children);
    }

    public function setParent(BaseNode $node): void
    {
        $this->parent = $node;
    }

    public function valid(): bool
    {
        return isset($this->children[$this->key()]);
    }
}