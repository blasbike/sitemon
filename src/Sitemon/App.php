<?php

declare(strict_types=1);

namespace Sitemon;

use Exception;

class App
{
    private $routes = [];


    /**
     * Read _GET query and run action with query params
     * Returns rendered HTML
     * @return string   HTML to display
     */
    public function run(): string
    {
        $actionName = $this->requestGet('action');

        return $this->runAction($actionName);
    }


    /**
     * Run web action from specified route
     * @param  string $actionName action to run
     * @return string             html to display
     */
    private function runAction(string $actionName): string
    {
        $routes = $this->getRoutes();

        if (!isset($routes[$actionName])) {
            throw new Exception(sprintf('Action %s is not defined', $actionName));
        }

        if (!is_callable($routes[$actionName])) {
            throw new Exception(sprintf('Route %s is not callable', json_encode($routes[$actionName])));
        }

        $action = call_user_func($routes[$actionName], $this->requestGetArray('params'));
        return $action;
    }


    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
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
