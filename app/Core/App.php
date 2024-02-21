<?php

namespace Core;

use Core\Container\Container;
use Model\Model;
use Request\Request;
use Service\Logger\LoggerService;

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

    public function bootstrap(): void
    {
        $pdo = $this->container->get(\PDO::class);
        Model::init($pdo);
    }

    public function run(): void
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestUri][$requestMethod])) {
            $this->bootstrap();

            $route = $this->routes[$requestUri][$requestMethod];
            $class = $route['class'];
            $method = $route['method'];
            $request = $route['request'];

            $obj = $this->container->get($class);

            if (isset($request)) {
                $request = new $route['request']($requestMethod, $requestUri, headers_list(), $_REQUEST);
            } else {
                $request = new Request($requestMethod, $requestUri, headers_list(), $_REQUEST);
            }

            try {
                $response = $obj->$method($request);

                if (isset($response['params'])) {
                    $params = $response['params'];

                    extract($params);
                }

                if (isset($response['view'])) {
                    $view = $response['view'];

                    require_once "./../View/$view";
                }


            } catch (\Throwable $exception) {
                $file = $exception->getFile();
                $line = $exception->getLine();
                $message = $exception->getMessage();

                LoggerService::error($file, $line, $message);

                require_once './../View/500.html';
            }
        } else {
            require_once './../View/404.html';
        }

    }

}