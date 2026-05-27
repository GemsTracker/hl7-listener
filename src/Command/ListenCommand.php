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
// use React\EventLoop\Loop;
use Zalt\Loader\ProjectOverloader;

/**
 * @package    Gems
 * @subpackage Hl7\Command
 * @since      Class available since version 1.0
 */
#[AsCommand(name: 'hl7:listen', description: 'Listen to HL7 2.4 messages')]
class ListenCommand extends Command
{
    public function __construct(
        protected readonly ProjectOverloader $projectOverloader,
    )
    {
        parent::__construct('hl7:listen');
    }

    protected function configure()
    {
        $this->addArgument('listener', InputArgument::REQUIRED, 'The listener from the config to use');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $listener = $input->getArgument('listener') ?? null;

        $output->writeln('Using listener: ' . $listener);

        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

        $this->projectOverloader->create('HL7\Server\HL7Listener', $listener);

        return Command::SUCCESS;
    }
}
