<?php

namespace Core;

use Core\Container\Container;
use Request\Request;
use Service\LoggerService;

class App
{
    private array $routes = [];

    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

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

                $obj = $this->container->get($class);

                if (method_exists($obj, $method))
                {
                    if (isset($request)) {
                        $request = new $route['request']($requestMethod, $requestUri, headers_list(), $_REQUEST);
                    } else {
                        $request = new Request($requestMethod, $requestUri, headers_list(), $_REQUEST);
                    }

                    try {
                        $obj->$method($request);

                    } catch (\Throwable $exception) {
                        $file = $exception->getFile();
                        $line = $exception->getLine();
                        $message = $exception->getMessage();

                        LoggerService::error($file, $line, $message);


                        require_once './../View/500.html';
                    }

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