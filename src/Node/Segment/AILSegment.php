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
 * @package    Gems
 * @subpackage Hl7\Node\Segment
 * @since      Class available since version 1.0
 */
class AILSegment extends Segment
{
    const IDENTIFIER = 'AIL';

    /**
     *
     * @param string $segmentName
     */
    public function __construct(string $segmentName = self::IDENTIFIER)
    {
        parent::__construct($segmentName);
    }
}