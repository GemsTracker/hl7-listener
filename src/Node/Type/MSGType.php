<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Node\Type
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Node\Type;

use Gems\Hl7\Node\Hl7Type;

/**
 *  MSG: Message Type
 *
 *  See http://hl7-definition.caristix.com:9010
 *
 *  SEQ    LENGTH    DT    OPT    TBL #    NAME
 *  MSG.1    0    ID    O        Message Type
 *  MSG.2    0    ID    O        Trigger Event
 *  MSG.3    0    ID    O        Message Structure
 *
 * @package    Gems
 * @subpackage Hl7\Node\Type
 * @since      Class available since version 1.0
 */
class MSGType extends Hl7Type
{
    /**
     *
     * @return String
     */
    public function getMessageStructure(): string
    {
        return (string) $this->_get(2);
    }

    /**
     *
     * @return String
     */
    public function getMessageType(): string
    {
        return (string) $this->_get(1);
    }

    /**
     *
     * @return String
     */
    public function getTriggerEvent(): string
    {
        return (string) $this->_get(2);
    }

}