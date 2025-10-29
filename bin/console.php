<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

$container = require __DIR__ . '/../config/container.php';
$config = $container->get('config');

$app = new Application();

if (isset($config['console'], $config['console']['commands'])) {

    $commands = [];
    foreach ($config['console']['commands'] as $command) {
        $commands[$command::getDefaultName()] = $command;
    }

    $commandLoader = new ContainerCommandLoader($container, $commands);

    $app->setCommandLoader($commandLoader);
}

$app->run();

