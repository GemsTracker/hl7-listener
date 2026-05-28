<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Node;

use Gems\Hl7\Exception\StructureException;

/**
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @since      Class available since version 1.0
 */
class Segment extends BaseNode
{
    protected string $escapeSequenceKey = 'field_delimiter';

    protected $segmentName = '';

    public function __construct(string $segmentName)
    {
        if(strlen($segmentName) !== 3 || !ctype_alnum($segmentName)) {
            throw new StructureException('Segment name should be 3 characters long, alphanumeric. Received: "'.$segmentName.'"');
        }
        $this->segmentName = $segmentName;
    }

    public function __toString()
    {
        $children = array_merge(array($this->segmentName), $this->children);

        return implode($this->getMessage()->getEscapeSequence($this->escapeSequenceKey), $children);
    }

    public function getSegmentName() {
        return $this->segmentName;
    }
}