<?php

declare(strict_types=1);

namespace Gems\Hl7;

use Gems\Util\RouteGroupTrait;

/**
 * The configuration provider for the Hl7 module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class Hl7ConfigProvider
{
    use RouteGroupTrait;

    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     *
     * @return mixed[]
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'hl7listener'  => $this->getHl7ListenerSettings(),
            'routes'       => $this->getRoutes(),
        ];
    }

    /**
     * Returns the container dependencies
     * @return mixed[]
     */
    public function getDependencies(): array
    {
        return [
            'factories'  => [
//              HomePageHandler::class => HomePageHandlerFactory::class,
            ],
        ];
    }

    public function getHl7ListenerSettings(): array
    {
        return [
            'test' => [
                'port' => 5000,
                // 'ipAdress' => '127.0.0.1',
                'dbPingTime' => 6,
                'checkAliveLog' => __DIR__ . '/../../data/logs/AdtCheckAlive.log',
                'listenerLog' => __DIR__ . '/../../data/logs/AdtListenerLog.log',
                'verbose' => true,
            ],
        ];
    }

    /**
     * Returns the route configuration
     *
     * @return mixed[]
     */
    public function getRoutes(): array
    {
        return [];
    }
}
