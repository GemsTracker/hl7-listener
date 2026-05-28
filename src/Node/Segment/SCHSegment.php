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
 *  SCH: Scheduling Activity Information
 *
 *  The SCH segment contains general information about the scheduled appointment.
 *
 *  See http://hl7-definition.caristix.com:9010
 *
 *  SEQ    LENGTH    DT    OPT    RPT / #    TBL #    NAME
 *  SCH.1    75    EI    C    1           Placer Appointment ID
 *  SCH.2    75    EI    C    1           Filler Appointment ID
 *  SCH.3    5    NM    C    1           Occurrence Number
 *  SCH.4    22    EI    O    1           Placer Group Number
 *  SCH.5    250    CE    O    1           Schedule ID
 *  SCH.6    250    CE    R    1           Event Reason
 *  SCH.7    250    CE    O    1    0276    Appointment Reason
 *  SCH.8    250    CE    O    1    0277    Appointment Type
 *  SCH.9    20    NM    O    1           Appointment Duration
 *  SCH.10    250    CE    O    1           Appointment Duration Units
 *  SCH.11    200    TQ    R    *           Appointment Timing Quantity
 *  SCH.12    250    XCN    O    *           Placer Contact Person
 *  SCH.13    250    XTN    O    1           Placer Contact Phone Number
 *  SCH.14    250    XAD    O    *           Placer Contact Address
 *  SCH.15    80    PL    O    1           Placer Contact Location
 *  SCH.16    250    XCN    R    *           Filler Contact Person
 *  SCH.17    250    XTN    O    1           Filler Contact Phone Number
 *  SCH.18    250    XAD    O    *           Filler Contact Address
 *  SCH.19    80    PL    O    1           Filler Contact Location
 *  SCH.20    250    XCN    R    *           Entered By Person
 *  SCH.21    250    XTN    O    *           Entered By Phone Number
 *  SCH.22    80    PL    O    1           Entered by Location
 *  SCH.23    75    EI    O    1           Parent Placer Appointment ID
 *  SCH.24    75    EI    C    1           Parent Filler Appointment ID
 *  SCH.25    250    CE    O    1    0278    Filler Status Code
 *  SCH.26    22    EI    C    *           Placer Order Number
 *  SCH.27    22    EI    C    *           Filler Order Number
 *
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @since      Class available since version 1.0
 */
class SCHSegment extends Segment
{
    const IDENTIFIER = 'SCH';

    public function __construct(string $segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }
}