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
 * MRG: Merge patient information
 *
 * The MRG segment provides receiving applications with information necessary to
 * initiate the merging of patient data as well as groups of records. It is intended
 * that this segment be used throughout the Standard to allow the merging of
 * registration, accounting, and clinical records within specific applications.
 *
 * See http://hl7-definition.caristix.com:9010
 *
 *
 * SEQ          LENGTH    DT    OPT    RPT / #    TBL #    NAME
 * MRG.1    250    CX    R    *        Prior Patient Identifier List
 * MRG.2    250    CX    B    *        Prior Alternate Patient ID
 * MRG.3    250    CX    O    1        Prior Patient Account Number
 * MRG.4    250    CX    B    1        Prior Patient ID
 * MRG.5    250    CX    O    1        Prior Visit Number
 * MRG.6    250    CX    O    1        Prior Alternate Visit ID
 * MRG.7    250    XPN    O    *        Prior Patient Name
 *
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @since      Class available since version 1.0
 */
class MRGSegment extends Segment
{
    const IDENTIFIER = 'MRG';

    /**
     *
     * @param string $segmentName
     */
    public function __construct(string $segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }

}