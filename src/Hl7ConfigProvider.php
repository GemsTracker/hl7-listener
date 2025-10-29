<?php

declare(strict_types=1);

namespace Gems\Hl7;

use Gems\Util\RouteGroupTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

/**
 * The configuration provider for the Hl7 module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
#[AsCommand(name: 'hl7:listen', description: 'Listen on a port')]
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
//            'console'      => $this->getConsoleSettings(),
//            'dependencies' => $this->getDependencies(),
//            'routes'       => $this->getRoutes(),
        ];
    }

    public function getConsoleSettings(): array
    {
        return [
            'resetPassword' => true,
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
