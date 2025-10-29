<?php

declare(strict_types=1);

use Gems\Helper\Env;
use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

// To enable or disable caching, set the `ConfigAggregator::ENABLE_CACHE` boolean in
// `config/autoload/local.php`.
$rootDir = dirname(__DIR__);

$env = Env::get('APP_ENV');

$cacheConfig = [
    'config_cache_path' => "$rootDir/data/cache/config-cache.php",
    'autoconfig' => [
        'cache_path' => "$rootDir/data/cache/autoconfig.config.php",
    ],
];

$dirConfig = [
    'rootDir' => $rootDir,
    'publicDir' => $rootDir . '/public',
];

$modules = require('modules.php');

$aggregator = new ConfigAggregator([
    \Mezzio\Helper\ConfigProvider::class,
    // Include cache configuration
    new ArrayProvider($cacheConfig),
    \Mezzio\ConfigProvider::class,
    \Mezzio\Router\ConfigProvider::class,
    \Mezzio\Router\FastRouteRouter\ConfigProvider::class,
    \Mezzio\Twig\ConfigProvider::class,
    \Laminas\Diactoros\ConfigProvider::class,
    new ArrayProvider($dirConfig),

    new PhpFileProvider($cacheConfig['autoconfig']['cache_path']),

    ...$modules,

    // Load application config in a pre-defined order in such a way that local settings
    // overwrite global settings. (Loaded as first to last):
    //   - `global.php`
    //   - `*.global.php`
    //   - `local.php`
    //   - `*.local.php`
    new PhpFileProvider(realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php'),
    new PhpFileProvider(realpath(__DIR__) . '/autoload/' . $env .'.php'),

    // Load development config if it exists
    new PhpFileProvider(realpath(__DIR__) . '/development.config.php'),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
