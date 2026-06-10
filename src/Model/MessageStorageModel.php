<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Model
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Model;

use Gems\Model\MetaModelLoader;
use Gems\Model\SqlTableModel;
use Zalt\Base\TranslatorInterface;
use Zalt\Model\Sql\SqlRunnerInterface;

/**
 * @package    Gems
 * @subpackage Hl7\Model
 * @since      Class available since version 1.0
 */
class MessageStorageModel extends SqlTableModel
{
    public function __construct(MetaModelLoader $metaModelLoader, SqlRunnerInterface $sqlRunner, TranslatorInterface $translate)
    {
        parent::__construct('hl7_messages', $metaModelLoader, $sqlRunner, $translate);
    }
}