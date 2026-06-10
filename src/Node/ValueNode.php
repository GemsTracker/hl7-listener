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
class ValueNode extends BaseNode
{
    public $value = null;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        if (count($this->children) === 0) {
            return (string) $this->value;
        }
        return implode($this->getMessage()->getEscapeSequence($this->escapeSequenceKey), $this->children);
    }
}