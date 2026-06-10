<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Node\Message
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Node\Message;

use Gems\Hl7\Node\Message;
use Gems\Hl7\Node\Segment\MSASegment;

/**
 * @package    Gems
 * @subpackage Hl7\Node\Message
 * @since      Class available since version 1.0
 */
class ACKMessage extends Message
{
    public function __construct(Message $incomingMessage, $responseCode = "AA")
    {
        $this->escapeSequences['cursor_return'] = chr(13);  // Fix incorrect setting;

        $msh = $incomingMessage->getMshSegment();

        // To copy the segment, we create a newMSH segment, and add a new field with a repetition that hold the string value from the incoming MSH
        $class = get_class($msh);
        $mshResponse = new $class('MSH');
        foreach($msh as $field) {
            $class = get_class($field);
            $newField = new $class();
            $mshResponse->append($newField);
            $class = get_class($field->children[0]);
            $newRepetition = new $class($field->__toString());
            $newField->append($newRepetition);
        }

        $mshResponse->setMessageType('ACK^' . $msh->getMessageStructure()); // Return second part

        // Flip sending and receiving application/facility
        $receivedApplication = $msh->getSendingApplication();
        $receivedFacility    = $msh->getSendingFacility();
        $sendingApplication  = $msh->getReceivingApplication();
        $sendingFacility     = $msh->getReceivingFacility();
        $mshResponse->setReceivingApplication($receivedApplication);
        $mshResponse->setReceivingFacility($receivedFacility);
        $mshResponse->setSendingApplication($sendingApplication);
        $mshResponse->setSendingFacility($sendingFacility);
        $this->offsetSet(0, $mshResponse);

        // Add the MSA segment
        $ackSegment = new MSASegment();
        $ackSegment->setAcknowledgementCode($responseCode);
        $ackSegment->setMessageControlId((string) $msh->getMessageControlId());
        $this->offsetSet(1, $ackSegment);
    }
}