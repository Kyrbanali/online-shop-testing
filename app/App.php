<?php


use Controller\ProductController;
use Controller\UserController;

class App
{
    private array $routes = [
        '/registrate' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getRegistrate',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'postRegistrate',
            ],
        ],
        '/login' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'getLogin',
            ],
            'POST' => [
                'class' => UserController::class,
                'method' => 'postLogin',
            ],
        ],
        '/logout' => [
            'GET' => [
                'class' => UserController::class,
                'method' => 'logout',
            ],
        ],
        '/catalog' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'getCatalog',
            ],
        ],
        '/processCart' => [
            'POST' => [
                'class' => ProductController::class,
                'method' => 'processCart',
            ],
        ],
        '/cart' => [
            'GET' => [
                'class' => ProductController::class,
                'method' => 'getCart',
            ],
        ],
    ];

    public function handleRequest()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$requestUri][$requestMethod]))
        {
            $route = $this->routes[$requestUri][$requestMethod];
            $class = $route['class'];
            $method = $route['method'];

            if (class_exists($class))
            {
                $obj = new $class();

                if (method_exists($obj, $method))
                {
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