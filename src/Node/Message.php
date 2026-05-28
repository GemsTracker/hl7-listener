<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Node
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Node;

use Gems\Hl7\Exception\StructureException;
use Gems\Hl7\Node\Segment\MSHSegment;

/**
 * @package    Gems
 * @subpackage Hl7\Node
 * @since      Class available since version 1.0
 */
class Message extends BaseNode
{
    protected array $escapeSequences = [
        'field_delimiter' => '|',
        'repeat_delimiter' => '~',
        'component_delimiter' => '^',
        'subcomponent_delimiter' => '&',
        'escape_delimiter' => '\\',
        'cursor_return' => '\r',
        ];

    protected string $escapeSequenceKey = 'cursor_return';

    public function getEscapeSequence(string $key): string
    {
        return $this->escapeSequences[$key] ?? '';
    }

    public function getEscapeSequences(): array
    {
        return $this->escapeSequences;
    }

    public function getMshSegment(): ?MSHSegment
    {
        return $this->getSegmentByClass(MSHSegment::class);
    }

    public function getSegmentByClass($class): mixed
    {
        foreach ($this->children as $child) {
            if ($child instanceof $class) {
                return $child;
            }
        }

        return null;
    }

    public function setEscapeSequences(array $escapeSequences): void
    {
        $this->escapeSequences = array_merge($this->escapeSequences, $escapeSequences);
    }
}