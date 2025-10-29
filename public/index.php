<?php

declare(strict_types=1);

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

//ini_set('display_errors', 'on');
//ini_set('display_startup_errors', 'on');
//error_reporting(E_ALL);
//ini_set('error_log', dirname(__DIR__) . '/data/logs/php_errors.log');

/**
 * Self-called anonymous function that creates its own scope and keeps the global namespace clean.
 */
(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require 'config/container.php';

    /** @var \Mezzio\Application $app */
    $app = $container->get(\Mezzio\Application::class);
    $factory = $container->get(\Mezzio\MiddlewareFactory::class);

    // Execute programmatic/declarative middleware pipeline and routing
    // configuration statements
    Gems\InitFunctions::pipeline($app, $factory, $container);
    Gems\InitFunctions::routes($app, $factory, $container);

    $app->run();
})();
