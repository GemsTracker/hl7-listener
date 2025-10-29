# Loggin

[Monolog](http://seldaek.github.io/monolog/) is used for logging. It is an extension to the [PSR-3 logger interface](https://www.php-fig.org/psr/psr-3/).

## Config

Reusable log file configurations can be done in the `log` namespace of the config
```php
return [
    'log' => [
        'myLogger' => [
            // Logger config
        ],
    ],   
];
```

By also configuring your logger to the `Gems\Factory\MonologFactory` factory:

```php 
return [
    'dependencies' => [
        'factories' => [
            'myLogger' => MonologFactory::class,
        ],
    ],
];
```

You can now inject it into your controller with that name

```php 
use Psr\Log\LoggerInterface

MyClass
{
    protected LoggerInterface $logger;
    
    public function __construct($myLogger)
    {
        $this->logger = $myLogger;
    }
}
```


### Writers

You can add writers to your config as an array

```php
return [
    'log' => [
        'myLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => LogLevel::NOTICE,
                    'options' => [
                        'stream' =>  'data/logs/embed-logging.log',
                    ],
                ],
            ],
        ],
    ],   
];
```
