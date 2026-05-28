<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Node\Segment;

use Gems\Hl7\Exception\ResponseException;
use Gems\Hl7\Node\Component;
use Gems\Hl7\Node\Field;
use Gems\Hl7\Node\Repetition;
use Gems\Hl7\Node\Segment;

/**
 * MSA segment, used in ACK message to acknowledge receiving a message
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ    LENGTH    DT    OPT    RPT / #    TBL #    NAME
 * MSA.1    2    ID    R    1    0008    Acknowledgment Code
 * MSA.2    20    ST    R    1        Message Control ID
 * MSA.3    80    ST    B    1        Text Message
 * MSA.4    15    NM    O    1        Expected Sequence Number
 * MSA.5    0    ID    W    1        Delayed Acknowledgment Type
 * MSA.6    250    CE    B    1    0357    Error Condition
 *
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @since      Class available since version 1.0
 */
class MSASegment extends Segment
{
    const IDENTIFIER = 'MSA';

    public function __construct(string $segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }

    /**
     * Set the acknowledgement code
     *
     * AA = Accept
     * AE = Error
     * AR = Reject
     *
     * enhanced mode
     *
     * CA = Commit Accept
     * CE = Commit Error
     * CR = Commit Reject
     */
    public function setAcknowledgementCode(string $code = "AA")
    {
        if (strlen($code) !== 2) {
            throw new ResponseException('Acknowledgement code should always be exactly 2 characters.');
        }

        $field = new Field();
        $field->setParent($this);

        $repetition = new Repetition();
        $field->append($repetition);
        $component = new Component($code);
        $repetition->append($component);

        $this->children[1] = $field;
    }

    public function setMessageControlId($id)
    {
        $field = new Field();
        $field->setParent($this);

        $repetition = new Repetition();
        $field->append($repetition);
        $component = new Component($id);
        $repetition->append($component);

        $this->children[2] = $field;
    }
}