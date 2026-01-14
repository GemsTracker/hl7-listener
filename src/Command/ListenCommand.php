<?php

declare(strict_types=1);

/**
 * @package    Gems
 * @subpackage Hl7\Command
 * @author     Matijs de Jong <mjong@magnafacta.nl>
 */

namespace Gems\Hl7\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @package    Gems
 * @subpackage Hl7\Command
 * @since      Class available since version 1.0
 */
#[AsCommand(name: 'hl7:listen', description: 'Listen to HL7 2.4 messages')]
class ListenCommand extends Command
{
    public function __construct()
    {
        parent::__construct('hl7:listen');
    }

    protected function configure()
    {
        $this->addArgument('port', InputArgument::REQUIRED, 'The port to listen on');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = intval($input->getArgument('port') ?? 0);

        $output->writeln('Listening on port: ' . $port);

        return Command::SUCCESS;
    }
}
