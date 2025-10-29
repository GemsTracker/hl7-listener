<?php

declare(strict_types=1);

return [
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
    'sites' => [
        'allowed' => [
            [
                'url' => 'https://localhost/',
            ],
        ],
    ],
    'survey' => [
        'limesurvey' => [
            'tokenUrlStart' => '?r=',
        ],
    ],
];
