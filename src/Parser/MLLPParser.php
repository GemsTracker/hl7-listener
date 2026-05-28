<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Parser
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Parser;

/**
 * MLLP stands for Minimal Lower Layer Protocol and is the lowest level of the HL7 2 protocal.
 *
 * It defines one start character and two end characters
 *
 * @package    Gems
 * @subpackage Hl7\Parser
 * @since      Class available since version 1.0
 */
class MLLPParser
{
    public static function enclose(string $data): string
    {
        return chr(11).$data.chr(28).chr(13);
    }

    public static function unwrap(string $data): string
    {
        if(substr($data, 0, 1) !== chr(11)) {
            throw new \InvalidArgumentException('Envelope does not start with <VT> (ASCII 11)');
        }

        if(substr($data, -2) !== chr(28).chr(13)) {
            throw new \InvalidArgumentException('Envelope does not end with <FS><CR> (ASCII 28, 13)');
        }

        return substr($data, 1, -2);
    }

}