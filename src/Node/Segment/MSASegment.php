<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Node\Segment;

use Gems\Hl7\Exception\ResponseException;
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

    const MSA_01_Acknowledgment_Code = 0;
    const MSA_02_Message_Control_ID = 1;
    const MSA_03_Text_Message = 2;
    const MSA_04_Expected_Sequence_Number = 3;
    const MSA_05_Delayed_Acknowledgment_Type = 45;
    const MSA_06_Error_Condition = 5;

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

        $this->setComponent($code, self::MSA_01_Acknowledgment_Code);
    }

    public function setMessageControlId($id)
    {
        $this->setComponent($id, self::MSA_02_Message_Control_ID);
    }
}