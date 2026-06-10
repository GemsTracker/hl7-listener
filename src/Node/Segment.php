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

    protected string $segmentName = '';

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

    /**
     * Utility function
     *
     * @param int $idx 1 based index
     * @param int $offset 0 basex index
     */
    protected function get(int $idx, ?int $offset = null): mixed
    {
        $object = $this->offsetExists($idx) ? $this->offsetGet($idx) : null;

        if ($object instanceof BaseNode) {
            if ($offset == 0 && $object instanceof ValueNode && count($object) == 0) {
                return $object->value;
            }

            if (!is_null($offset)) {
                return $object->offsetExists($offset) ? $object->offsetGet($offset) : null;
            }
        }

        return $object;
    }

    /**
     * Utility function
     *
     * @param int $idx 1 based index
     * @param int $offset 0 basex index
     */
    protected function getDateTime(int $idx, ?int $offset = null): ?\DateTimeInterface
    {
        $stamp = (string) $this->get($idx, $offset);

        if (strlen($stamp) < 4) {
            return null;
        }

        $year = substr($stamp, 0, 4);
        $month = substr($stamp, 4, 2);
        if (false === $month || $month == 0) {
            $month = 7;
        }
        $day = substr($stamp, 6, 2);
        if (false === $day || $day == 0) {
            $day = 1;
        }
/**/
        $dateObject = new \DateTime();
        $dateObject->setDate((int) $year, (int) $month, (int)  $day);

        $hour   = 0;
        $minute = 0;
        $second = 0;
        if (strlen($stamp) >= 12) {
            $hour   = substr($stamp, 8, 2);
            $minute = substr($stamp, 10, 2);

            if (strlen($stamp) >= 14) {
                $second = substr($stamp, 12, 2);
            }
        }
        $dateObject->setTime((int) $hour, (int) $minute, (int) $second);

        return $dateObject;
    }

    protected function getInt(int $idx, ?int $offset = null): ?int
    {
        $object = $this->get($idx, $offset);

        if ($object instanceof Repetition) {
            if (isset($object->value)) {
                return (int) $object->value;
            }
            return (int) $object->offsetGet(0);
        }
        if ($object instanceof ValueNode) {
            return (int) $object->value;
        }

        return null;
    }

    public function getSegmentName()
    {
        return $this->segmentName;
    }

    protected function getString(int $idx, ?int $offset = null): ?string
    {
        return (string) $this->get($idx, $offset);
    }

    protected function getSub(int $idx, ?int $offset = null, $sub = 0, $default = null): mixed
    {
        $content = $this->get($idx, $offset);

        if ($content instanceof ValueNode && $sub == 0 && count($content) == 0) {
            $value = $content->value;
        } elseif ($content->offsetExists($sub)) {
            $value = $content->offsetGet($sub);
        } else {
            $value = $default;
        }

        if ((string) $value === '""') {
            $value = null;
        }
        return $value;
    }

    protected function setComponent(mixed $value, int $idx): void
    {
        $field = new Field();
        $field->setParent($this);

        $repetition = new Repetition();
        $field->append($repetition);
        $component = new Component($value);
        $repetition->append($component);

        $this->children[$idx] = $field;
    }

    protected function setObject(mixed $value, int $idx, ?int $offset = null): bool
    {
        $object = $this->offsetExists($idx) ? $this->offsetGet($idx) : null;

        if (!is_null($offset) && $object instanceof BaseNode) {
            if ($object->offsetExists($offset)) {
                $object->offsetSet($offset, $value);
                return true;
            }
            $object->offsetSet($idx, $value);
            return true;
        }
        return false;
    }

    protected function setValue(mixed $value, int $idx, ?int $offset = null) : bool
    {
        $object = $this->offsetExists($idx) ? $this->offsetGet($idx) : null;

        if (!is_null($offset) && $object instanceof BaseNode) {
            if ($object->offsetExists($offset)) {
                $target = $object->offsetGet($offset);
            } else {
                $target = $object;
            }
            if ($target instanceof ValueNode) {
                $target->value = $value;
                return true;
            }
        }
        return false;

    }
}