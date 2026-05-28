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
 * EVN segment
 *
 * See http://hl7-definition.caristix.com:9010
 *
 * SEQ    LEN    DT    OPT    RP/#    TBL#    ITEM#    ELEMENT NAME
 * 1    3    ID    B        0003    00099    Event Type Code
 * 2    26    TS    R            00100    Recorded Date/Time
 * 3    26    TS    O            00101    Date/Time Planned Event
 * 4    3    IS    O        0062    00102    Event Reason Code
 * 5    250    XCN    O    Y    0188    00103    Operator ID
 * 6    26    TS    O            01278    Event Occurred
 * 7    180    HD    O            01534    Event Facility
 *
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @since      Class available since version 1.0
 */
class EVNSegment extends Segment
{
    const IDENTIFIER = 'EVN';

    public function __construct(string $segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }
}