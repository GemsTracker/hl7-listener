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
 *  NTE: Notes and Comments
 *
 *  The NTE segment is defined here for inclusion in messages defined in other chapters. It is commonly used for sending notes and comments.
 *
 *  See http://hl7-definition.caristix.com:9010
 *
 *  SEQ    LENGTH    DT    OPT    RPT / #    TBL #    NAME
 *  NTE.1    4    SI    O    1        Set ID - NTE
 *  NTE.2    8    ID    O    1    0105    Source of Comment
 *  NTE.3    65536    FT    O    *        Comment
 *  NTE.4    250    CE    O    1    0364    Comment Type
 *
 * @subpackage Hl7\Node\Segment
 * @since      Class available since version 1.0
 */
class NTESegment extends Segment
{
    const IDENTIFIER = 'NTE';

    public function __construct(string $segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }
}