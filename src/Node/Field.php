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
class Field extends BaseNode
{
    protected string $escapeSequenceKey = 'repeat_delimiter';
}