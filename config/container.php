<?php

declare(strict_types=1);

use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Dotenv\Dotenv;
use Gems\Config\AutoConfigurator;

$dotenv = new Dotenv();
$dotenv->loadEnv(dirname(dirname(__FILE__)).'/.env');

// Load configuration
$config = require __DIR__ . '/config.php';

$autoConfigurator = new AutoConfigurator($config);
$config = $autoConfigurator->autoConfigure();

// Uncomment to store a configuration overview
// file_put_contents('data/logs/config.php', "Services Config: \n" .  print_r($config, true) . "\n");

$dependencies                       = $config['dependencies'];
$dependencies['services']['config'] = $config;

// Build container
return new ServiceManager($dependencies);
