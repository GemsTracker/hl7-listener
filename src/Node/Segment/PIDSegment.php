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
 *  PID segment
 *
 *  See http://hl7-definition.caristix.com:9010
 *
 *  SEQ    LEN    DT    OPT    RP/#    TBL#    ITEM#    ELEMENT NAME
 *  1    4    SI    O               00104    Set ID - PID
 *  2    20    CX    B               00105    Patient ID
 *  3    250    CX    R    Y           00106    Patient Identifier List
 *  4    20    CX    B    Y           00107    Alternate Patient ID - PID
 *  5    250    XPN    R    Y           00108    Patient Name
 *  6    250    XPN    O    Y           00109    Mother’s Maiden Name
 *  7    26    TS    O               00110    Date/Time of Birth
 *  8    1    IS    O       0001    00111    Administrative Sex
 *  9    250    XPN    B    Y           00112    Patient Alias
 *  10    250    CE    O    Y   0005    00113    Race
 *  11    250    XAD    O    Y           00114    Patient Address
 *  12    4    IS    B        0289    00115    County Code
 *  13    250    XTN    O    Y           00116    Phone Number - Home
 *  14    250    XTN    O    Y           00117    Phone Number - Business
 *  15    250    CE    O        0296    00118    Primary Language
 *  16    250    CE    O        0002    00119    Marital Status
 *  17    250    CE    O        0006    00120    Religion
 *  18    250    CX    O               00121    Patient Account Number
 *  19    16    ST    B               00122    SSN Number - Patient
 *  20    25    DLN    O               00123    Driver's License Number - Patient
 *  21    250    CX    O    Y           00124    Mother's Identifier
 *  22    250    CE    O    Y    0189    00125    Ethnic Group
 *  23    250    ST    O               00126    Birth Place
 *  24    1    ID    O        0136    00127    Multiple Birth Indicator
 *  25    2    NM    O               00128    Birth Order
 *  26    250    CE    O    Y    0171    00129    Citizenship
 *  27    250    CE    O        0172    00130    Veterans Military Status
 *  28    250    CE    B        0212    00739    Nationality
 *  29    26    TS    O               00740    Patient Death Date and Time
 *  30    1    ID    O        0136    00741    Patient Death Indicator
 *  31    1    ID    O        0136    01535    Identity Unknown Indicator
 *  32    20    IS    O    Y    0445    01536    Identity Reliability Code
 *  33    26    TS    O               01537    Last Update Date/Time
 *  34    40    HD    O               01538    Last Update Facility
 *  35    250    CE    C        0446    01539    Species Code
 *  36    250    CE    C        0447    01540    Breed Code
 *  37    80    ST    O            0   1541    Strain
 *  38    250    CE    O    2    0429    01542    Production Class Code
 *
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @since      Class available since version 1.0
 */
class PIDSegment extends Segment
{
    const IDENTIFIER = 'PID';

    public function __construct(string $segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }
}