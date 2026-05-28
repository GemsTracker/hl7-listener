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
class Hl7Type
{
    /**
     * @var BaseNode
     */
    protected BaseNode $content;

    public function __construct(BaseNode $node)
    {
        $this->content = $node;
    }

    public function _get($idx, $default = null)
    {
        $realIdx = $idx - 1;

        if ($realIdx == 0 && count($this->content) == 0 && ($this->content instanceof ValueNode)) {
            $value = $this->content->value;
        } elseif ($this->content->offsetExists($realIdx)) {
            $value = $this->content->offsetGet($realIdx);
        } else {
            $value = $default;
        }

        if ((string) $value === '""') $value = null;
        return $value;
    }

    public function __toString() {
        return (string) $this->content;
    }
}