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
    public function run()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        switch ($requestUri)
        {
            case '/registrate':

                $obj = new Controller\UserController();

                switch ($requestMethod)
                {
                    case 'POST':
                        $obj->postRegistrate();
                        break;

                    case 'GET':
                        $obj->getRegistrate();
                        break;

                    default:
                        echo "метод $requestMethod не поддерживается для запроса $requestUri";
                        break;
                }
                break;

            case '/login':

                $requestMethod = $_SERVER['REQUEST_METHOD'];

                $obj = new Controller\UserController();

                switch ($requestMethod)
                {
                    case 'POST':

                        $obj->postLogin();
                        break;

                    case 'GET':

                        $obj->getLogin();
                        break;

                    default:
                        echo "метод $requestMethod не поддерживается для запроса $requestUri";
                        break;
                }
                break;

            case '/logout':

                switch ($requestMethod)
                {
                    case 'GET':
                        $obj = new Controller\UserController();

                        $obj->logout();
                        header('Location: /login');
                        break;

                    default:
                        echo "метод $requestMethod не поддерживается для запроса $requestUri";
                        break;

                }

                break;

            case '/catalog':

                switch ($requestMethod)
                {
                    case 'GET':
                        $obj = new ProductController();
                        $obj->getCatalog();


                        break;

                    default:
                        echo "метод $requestMethod не поддерживается для запроса $requestUri";
                        break;
                }

                break;

            case '/processCart':

                switch ($requestMethod)
                {
                    case 'POST':
                        $obj = new Controller\ProductController();
                        $obj->processCart();
                        header('Location: /catalog');
                        break;

                    default:
                        echo "метод $requestMethod не поддерживается для запроса $requestUri";
                        break;
                }
                break;

            case '/product':

                switch ($requestMethod)
                {
                    case 'GET':

                        break;

                    default:
                        echo "метод $requestMethod не поддерживается для запроса $requestUri";
                        break;

                }
                break;

            case '/cart':
                switch ($requestMethod)
                {
                    case 'GET':
                        $obj = new Controller\ProductController();
                        $obj->getCart();
                        break;

                    default:
                        echo "метод $requestMethod не поддерживается для запроса $requestUri";
                        break;

                }
                break;

            default:
                require_once './../View/404.html';
                break;
        }
    }

}