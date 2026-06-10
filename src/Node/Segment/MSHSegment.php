<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Node\Segment;

use Gems\Hl7\Node\Segment;

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

    const MSH_02_Encoding_Characters = 0;
    const MSH_03_Sending_Application = 1;
    const MSH_04_Sending_Facility = 2;
    const MSH_05_Receiving_Application = 3;
    const MSH_06_Receiving_Facility = 4;
    const MSH_07_DateTime_Of_Message = 5;
    const MSH_08_Security = 6;
    const MSH_09_Message_Type = 7;
    const MSH_10_Message_Control_ID = 8;
    const MSH_11_Processing_ID = 9;
    const MSH_12_Version_ID = 10;
    const MSH_13_Sequence_Number = 11;
    const MSH_14_Continuation_Pointer = 12;
    const MSH_15_Accept_Acknowledgment_Type = 13;
    const MSH_16_Application_Acknowledgment_Type = 14;
    const MSH_17_Country_Code = 15;
    const MSH_18_Character_Set = 16;
    const MSH_19_Principal_Language =  17;
    const MSH_20_Alternate_Character_Set = 18;
    const MSH_21_Message_Profile_Identifier = 19;

    public function __construct(string $segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }

    public function getCharacterset(): string
    {
        if ($this->offsetExists(self::MSH_18_Character_Set)) {
            return $this->get(self::MSH_18_Character_Set, 0)->value;
        }

        // Fallback, try to detect encoding
        return \mb_detect_encoding($this->__toString(), "auto");
    }

    public function getDateTimeOfMessage(): ?\DateTimeInterface
    {
        return $this->getDateTime(self::MSH_07_DateTime_Of_Message,0);
    }

    public function getMessageControlId(): int
    {
        return $this->getInt(self::MSH_10_Message_Control_ID, 0);
    }

    public function getMessageType(): string
    {
        return (string) $this->get(self::MSH_09_Message_Type, 0);
    }

    /**
     *
     * @return String
     */
    public function getMessageMainType(): mixed
    {
        return $this->getSub(self::MSH_09_Message_Type, 0, 0);
    }

    /**
     *
     * @return String
     */
    public function getMessageStructure(): mixed
    {
        echo (string) $this->children[self::MSH_09_Message_Type][0][1] . "\n";
        return $this->getSub(self::MSH_09_Message_Type, 0, 1);
    }

    public function getProcessingId()
    {
        return $this->getString(self::MSH_11_Processing_ID, 0);
    }

    public function getReceivingApplication(): string
    {
        return $this->getString(self::MSH_05_Receiving_Application, 0); //string) $this->children[3][0]->value;
    }

    public function getReceivingFacility(): string
    {
        return $this->getString(self::MSH_06_Receiving_Facility, 0); //string) $this->children[4][0]->value;
    }

    public function getSendingApplication(): string
    {
        return $this->getString(self::MSH_03_Sending_Application, 0); //string) $this->children[1][0]->value;
    }

    public function getSendingFacility(): string
    {
        return $this->getString(self::MSH_04_Sending_Facility, 0); //string) $this->children[2][0]->value;
    }

    public function getVersionId()
    {
        return $this->getString(self::MSH_12_Version_ID, 0); // $this->getString(10, 0);
    }

    public function setMessageType(string $string): void
    {
        $this->setObject($string, self::MSH_09_Message_Type, 0);
        // $this->children[7][0] = $string;
    }

    public function setReceivingApplication(string $string): void
    {
        $this->setObject($string, self::MSH_05_Receiving_Application, 0);
        // $this->children[3][0]->value = $string;
    }

    public function setReceivingFacility(string $string): void
    {
        $this->setObject($string, self::MSH_06_Receiving_Facility, 0);
        // $this->children[4][0]->value = $string;
    }

    public function setSendingApplication(string $string): void
    {
        $this->setObject($string, self::MSH_03_Sending_Application, 0);
        // $this->children[1][0]->value = $string;
    }

    public function setSendingFacility(string $string): void
    {
        $this->setObject($string, self::MSH_04_Sending_Facility, 0);
        // $this->children[2][0]->value = $string;
    }
}