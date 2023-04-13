<?php

namespace Tabata\System;

class Routes {

    private $routes;
    private const DEFAULT_ROUTE = 'default';

    public function __construct()
    {
        $this->routes = [];
    }

    public function addRoute($name, $method)
    {
        $this->routes[$name] = $method;
    }

    public function getRoute($name)
    {
        if (empty($this->routes[$name])) {
            return $this->routes[self::DEFAULT_ROUTE];
        }

        return $this->routes[$name];
    }
}