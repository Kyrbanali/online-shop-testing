<?php


namespace Core;

use Controller\ProductController;
use Controller\UserController;

class App
{
    private array $routes = [];

    public function getRoutes()
    {
        return $this->routes;

    }
    public function get($route, $class, $methodName) : void
    {
        $this->hasRoute($route);
        $this->routes[$route]['GET'] = [
            'class' => $class,
            'method' => $methodName,
        ];
    }
    public function post($route, $class, $methodName) : void
    {
        $this->hasRoute($route);
        $this->routes[$route]['POST'] = [
            'class' => $class,
            'method' => $methodName,
        ];

    }
    private function hasRoute($route)
    {
        if (!isset($this->routes[$route]))
        {
            $this->routes[$route] = [];
        }
    }
    public function handleRequest()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestUri][$requestMethod])) {
            $route = $this->routes[$requestUri][$requestMethod];
            $class = $route['class'];
            $method = $route['method'];

            if (class_exists($class)) {
                $obj = new $class();

                if (method_exists($obj, $method)) {
                    $obj->$method();
                } else {
                    echo "Метод $method не найден в классе $class";
                }
            } else {
                echo "Класс $class не найден";
            }
        } else {
            echo "Маршрут $requestUri с методом $requestMethod не найден";
        }

    }

}