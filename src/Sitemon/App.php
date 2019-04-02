<?php

declare(strict_types=1);

namespace Sitemon;

use Exception;

class App
{
    /**
     * routes defined in config/routes.php
     * @var array
     */
    private $routes = [];


    /**
     * default route when not provided, defined in config/routes.php
     * @var string
     */
    private $defaultRoute;


    /**
     * Read _GET query and run action with query params
     * Outputs rendered HTML
     * @return void
     */
    public function run(): void
    {
        $actionName = $this->requestGet('action') ?: $this->getDefaultRoute();

        $this->runAction($actionName);
    }


    /**
     * Run a web action from specified route
     * @param  string $actionName action to run
     * @return void
     */
    private function runAction(string $actionName): void
    {
        $routes = $this->getRoutes();

        if (!isset($routes[$actionName])) {
            throw new Exception(sprintf('Action %s is not defined', $actionName));
        }

        if (!is_callable($routes[$actionName])) {
            throw new Exception(sprintf('Route %s is not callable', json_encode($routes[$actionName])));
        }

        call_user_func([new $routes[$actionName][0], $routes[$actionName][1]], $this->requestGetArray('params'));
    }


    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }


    public function setDefaultRoute(string $route): void
    {
        $this->defaultRoute = $route;
    }


    public function getDefaultRoute(): string
    {
        return $this->defaultRoute;
    }


    public function getRoutes(): array
    {
        return $this->routes;
    }


    private function requestGet(string $param): string
    {
        return $_GET[$param] ?? '';
    }


    private function requestGetArray(string $param): array
    {
        return $_GET[$param] ?? [];
    }
}
