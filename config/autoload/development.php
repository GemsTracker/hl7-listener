<?php

declare(strict_types=1);

use Gems\Communication\Http\DevMailSmsClient;
use Gems\Communication\Http\SmsClientInterface;
use Laminas\ConfigAggregator\ConfigAggregator;
use Mezzio\Container\WhoopsErrorResponseGeneratorFactory;
use Mezzio\Container\WhoopsFactory;
use Mezzio\Container\WhoopsPageHandlerFactory;
use Mezzio\Middleware\ErrorResponseGenerator;
use Mezzio\Session\Cache\CacheSessionPersistence;
use Mezzio\Session\SessionPersistenceInterface;

return [
    ConfigAggregator::ENABLE_CACHE => false,
//    'cache' => [
//        'adapter' => 'redis',
//        'dsn' => 'rediss://redis-server-pulse-redis-server?ssl[verify_peer_name]=0&ssl[verify_peer]=0',
//    ],
//    'certificates' => [
//        'public' => null, //getenv('OAUTH2_PUBLIC_KEY') ? str_replace('\n', "\n", getenv('OAUTH2_PUBLIC_KEY')) : null,
//        'private' => null, //getenv('OAUTH2_PRIVATE_KEY') ? str_replace('\n', "\n", getenv('OAUTH2_PRIVATE_KEY')) : null,
//    ],
    'debug' => true,
    'dependencies' => [
        'factories' => [
            ErrorResponseGenerator::class => WhoopsErrorResponseGeneratorFactory::class,
            'Mezzio\Whoops'               => WhoopsFactory::class,
            'Mezzio\WhoopsPageHandler'    => WhoopsPageHandlerFactory::class,
        ],
        'aliases' => [
            SmsClientInterface::class => DevMailSmsClient::class,
//            SessionPersistenceInterface::class => CacheSessionPersistence::class,
        ],
    ],
//    'email' => [
////        'dsn' => 'smtp://smtptest-mailpit:587',
//        'site' => 'pulse@equipezorgbedrijven.nl',
//    ],
    'mezzio-session-cache' => [
        'cookie_name' => '__Secure-gems_session',
        'cookie_secure' => true,
        'cookie_http_only' => true,
        'cookie_same_site' => 'Lax', // Lax on the test site, strict otherwise
    ],
    'mezzio-session-php' => [
        'cookie_name' => '__Secure-gems_session',
        'cookie_secure' => true,
        'cookie_http_only' => true,
        'cookie_same_site' => 'Lax', // Lax on the test site, strict otherwise
    ],
    'password' => [
        'default' => [
            'notTheName' => 1,
            //'inPasswordList' => '../library/Gems/docs/weak-lst',
        ],
        'guest' => [
            'capsCount' => 1,
        ],
        'staff' => [
            'capsCount' => 1,
            'lowerCount' => 1,
            'minLength' => 8,
            'numCount' => 0,
            'notAlphaCount' => 1,
            'notAlphaNumCount' => 0,
            'maxAge' => 365,
        ],
        'researcher' => [
            'maxAge' => 1,
        ],
    ],
    'sites' => [
        'allowed' => [
            [
                'url' => 'https://localhost',
            ],
        ],
    ],
    'survey' => [
        'limesurvey' => [
            'tokenUrlStart' => '?r=',
        ],
    ],
    'whoops' => [
        'json_exceptions' => [
            'display'    => true,
            'show_trace' => true,
            'ajax_only'  => true,
        ],
    ], //*/
];
