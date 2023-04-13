<?php

namespace Tabata;

use Symfony\Component\Yaml\Yaml;

class Begin {

    public const DEFAULT_ROUTE = 'default';
    public const ERROR_ROUTE = 'default';

    public static function run()
    {
        // Load the YAML file into a string
        $yaml = file_get_contents('routes.yaml');
        $routes = Yaml::parse($yaml);

        $requestedRoute = self::getRequestedRoute();

        if (empty($requestedRoute)) {
            $route = $routes[self::DEFAULT_ROUTE];
        } else {
            $route = $routes[$requestedRoute] ?? [];

            if (empty($route)) {
                $route = $routes[self::DEFAULT_ROUTE];
            }

            if (!method_exists($route['controller'], $route['method'])) {
                $route = $routes[self::ERROR_ROUTE];
            }
        }

        $params = [];
        $args = new \ReflectionMethod($route['controller'], $route['method']);
        $args = $args->getParameters();

        if (!empty($args)) {
            if (count($args) !== count($route['params'])) {
                echo 'invalid parameters for ' . $route['method'] . '() on ' . $route['controller'];
                exit;
            }

            foreach ($route['params'] as $param => $type) {
                $paramVal = @$_REQUEST[$param];

                switch ($type) {
                    case 'int':
                        $params[] = (int) $paramVal;
                        break;
                    case 'array':
                        $params[] = (array) $paramVal;
                        break;
                    default:
                        $params[] = $paramVal;
                }
            }
        }

        // display compiled page
        $controller = new $route['controller'];
        call_user_func_array([$controller, $route['method']], $params);
        exit;
    }

    private static function getRequestedRoute()
    {
        $url = parse_url("//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}", PHP_URL_PATH);
        $url = str_replace('/index.php', '', $url);

        $urlSegments = explode('/', $url);

        if (isset($urlSegments[0]) && empty($urlSegments[0])) {
            unset($urlSegments[0]);

            if (isset($urlSegments[1])) {
                // reset array keys
                $urlSegments = array_values($urlSegments);
            }
        }

        return implode('_', $urlSegments);
    }
}