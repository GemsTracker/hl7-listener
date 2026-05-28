<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Node\Segment;

use Gems\Hl7\Node\Segment;
use Gems\Hl7\Node\Type\MSGType;

/**
 * MSH segment
 *
 * Special: index should be -1 since we miss the first field setting the separator
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ    LENGTH    DT    OPT    RPT / #    TBL #    NAME
 * MSH.1    1    ST    R    1        Field Separator
 * MSH.2    4    ST    R    1        Encoding Characters
 * MSH.3    227    HD    O    1    0361    Sending Application
 * MSH.4    227    HD    O    1    0362    Sending Facility
 * MSH.5    227    HD    O    1    0361    Receiving Application
 * MSH.6    227    HD    O    1    0362    Receiving Facility
 * MSH.7    26    TS    R    1        Date/Time Of Message
 * MSH.8    40    ST    O    1        Security
 * MSH.9    15    MSG    R    1        Message Type
 * MSH.10    20    ST    R    1        Message Control ID
 * MSH.11    3    PT    R    1        Processing ID
 * MSH.12    60    VID    R    1        Version ID
 * MSH.13    15    NM    O    1        Sequence Number
 * MSH.14    180    ST    O    1        Continuation Pointer
 * MSH.15    2    ID    O    1    0155    Accept Acknowledgment Type
 * MSH.16    2    ID    O    1    0155    Application Acknowledgment Type
 * MSH.17    3    ID    O    1    0399    Country Code
 * MSH.18    16    ID    O    *    0211    Character Set
 * MSH.19    250    CE    O    1        Principal Language Of Message
 * MSH.20    20    ID    O    1    0356    Alternate Character Set Handling Scheme
 * MSH.21    427    EI    O    *        Message Profile Identifier
 *
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @since      Class available since version 1.0
 */
class MSHSegment extends Segment
{
    const IDENTIFIER = 'MSH';

    public function __construct(string $segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }

    public function getCharacterset(): string
    {
        if (count($this->children) > 16) {
            return $this->children[16][0]->value;
        }

        // Fallback, try to detect encoding
        return \mb_detect_encoding($this->__toString(), "auto");
    }

    public function getMessageControlId()
    {
        return $this->children[8][0];
    }

    public function getReceivingApplication(): string
    {
        return (string) $this->children[3][0]->value;
    }

    public function getReceivingFacility(): string
    {
        return (string) $this->children[4][0]->value;
    }

    public function getSendingApplication(): string
    {
        return (string) $this->children[1][0]->value;
    }

    public function getSendingFacility(): string
    {
        return (string) $this->children[2][0]->value;
    }


    public function getMessageType(): MSGType
    {
        return new MSGType($this->children[7][0] ?? null);
    }

    public function setMessageType(string $string): void
    {
        $this->children[7][0] = $string;
    }

    public function setReceivingApplication(string $string): void
    {
        $this->children[3][0]->value = $string;
    }

    public function setReceivingFacility(string $string): void
    {
        $this->children[4][0]->value = $string;
    }

    public function setSendingApplication(string $string): void
    {
        $this->children[1][0]->value = $string;
    }

    public function setSendingFacility(string $string): void
    {
        $this->children[2][0]->value = $string;
    }
}