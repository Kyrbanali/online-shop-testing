<?php


namespace Core;

use Controller\ProductController;
use Controller\UserController;
use Request\Request;

class App
{
    private array $routes = [];

    public function getRoutes() : array
    {
        return $this->routes;
    }

    public function get(string $route, string $class, string $methodName, string $request = null) : void
    {
        $this->routes[$route]['GET'] = [
            'class' => $class,
            'method' => $methodName,
            'request' => $request,
        ];
    }

    public function post(string $route, string $class, string $methodName, string $request = null) : void
    {
        $this->routes[$route]['POST'] = [
            'class' => $class,
            'method' => $methodName,
            'request' => $request,
        ];

    }

    public function handleRequest()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestUri][$requestMethod])) {
            $route = $this->routes[$requestUri][$requestMethod];
            $class = $route['class'];
            $method = $route['method'];
            $request = $route['request'];

            if (class_exists($class)) {
                $obj = new $class();

                if (method_exists($obj, $method))
                {
                    if (isset($request)) {
                        $request = new $route['request']($requestMethod, $requestUri, headers_list(), $_REQUEST);
                    } else {
                        $request = new Request($requestMethod, $requestUri, headers_list(), $_REQUEST);
                    }
                    $obj->$method($request);
                } else {
                    echo "Метод $method не найден в классе $class";
                }
            } else {
                echo "Класс $class не найден";
            }
        } else {
            require_once './../View/404.html';
        }

    }

}