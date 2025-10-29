<?php

require 'vendor/autoload.php';
$config = require 'config/config.php';

return
[
    'paths' => [
        'migrations' => $config['migrations']['migrations'],
        'seeds' => $config['migrations']['seeds'],
    ],
    'environments' => [
        'default_migration_table' => 'gems__migration_log',
        'default_environment' => 'production',
        'production' => [
            'adapter' => 'mysql',
            'host' => $config['db']['host'],
            'name' => $config['db']['database'],
            'user' => $config['db']['username'],
            'pass' => $config['db']['password'],
            'port' => '3306',
        ],
    ],
    'version_order' => 'creation'
];
