<?php

declare(strict_types=1);

namespace App;

use App\Handler\HomeHandler;
use App\Handler\HomePageHandler;
use App\Handler\HomePageHandlerFactory;
use Gems\AuthNew\AuthenticationMiddleware;
use Gems\Helper\Env;
use Gems\Middleware\LocaleMiddleware;
use Gems\Middleware\MenuMiddleware;
use Gems\Middleware\SecurityHeadersMiddleware;
use Gems\Util\RouteGroupTrait;
use Mezzio\Csrf\CsrfMiddleware;
use Mezzio\Flash\FlashMessageMiddleware;
use Mezzio\Session\SessionMiddleware;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
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
            'app'          => $this->getAppSettings(),
//            'auth'         => $this->getAuthSettings(),
//            'console'      => $this->getConsoleSettings(),
            'db'           => $this->getDbSettings(),
            'dependencies' => $this->getDependencies(),
            'email'        => $this->getEmailSettings(),
            'locale'       => $this->getLocaleSettings(),
            'templates'    => $this->getTemplates(),
            'routes'       => $this->getRoutes(),
            'roles'        => $this->getRoles(),
        ];
    }

    protected function getAppSettings(): array
    {
        return [
            'name' => 'GemsTracker',
            'show_title' => true,
            'show_env' => 'short',
        ];
    }

    protected function getAuthSettings(): array
    {
        return [
            'allowLoginOnOtherOrganization' => true,
        ];
    }

    public function getConsoleSettings(): array
    {
        return [
            'resetPassword' => true,
        ];
    }

    /**
     * @return boolean[]|string[]
     */
    public function getDbSettings(): array
    {
        return [
            'driver'    => 'Mysqli',
            'host'      => Env::get('DB_HOST'),
            'username'  => Env::get('DB_USER'),
            'password'  => Env::get('DB_PASS'),
            'database'  => Env::get('DB_NAME'),
            'charset'   => 'utf8',
            'options'   => ['buffer_results' => true],
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
                HomePageHandler::class => HomePageHandlerFactory::class,
            ],
        ];
    }

    public function getEmailSettings(): array
    {
        return  [
            'dsn' => 'smtp://localhost:25',
            'site' => 'dcsw@magnafacta.nl',
        ];
    }

    public function getLocaleSettings(): array
    {
        return [
            'availableLocales' => [
                'en',
                'nl',
//                'de',
//                'fr',
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
        return $this->routeGroup(['middleware' =>
            [
                SecurityHeadersMiddleware::class,
                SessionMiddleware::class,
                FlashMessageMiddleware::class,
                CsrfMiddleware::class,
                LocaleMiddleware::class,
                AuthenticationMiddleware::class,

                MenuMiddleware::class,
            ]
        ],
        [
            [
                'name' => 'home',
                'path' => '/',
                'middleware' => HomeHandler::class,
                'allowed_methods' => ['GET'],
            ],
        ]);
    }

    /**
     * Returns the templates configuration
     *
     * @return mixed[]
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app'    => ['templates/app'],
                'error'  => ['templates/error'],
            ],
        ];
    }

    /**
     * Returns the roles defined by this project
     *
     * @return mixed[]
     */
    public function getRoles(): array
    {
        return [
        ];
    }
}
