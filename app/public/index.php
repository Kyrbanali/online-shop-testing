<?php


$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestUri)
{
    case '/registrate':

        require_once './../Controller/UserController.php';


        $obj = new UserController();

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

        require_once './../Controller/UserController.php';

        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $obj = new UserController();

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

        require_once './../Controller/UserController.php';
        switch ($requestMethod)
        {
            case 'GET':
                $obj = new UserController();

                $obj->postLogout();
                header('Location: /login');
                break;

            default:
                echo "метод $requestMethod не поддерживается для запроса $requestUri";
                break;

        }

        break;

    case '/catalog':
        require_once './../Controller/ProductController.php';

        switch ($requestMethod)
        {
            case 'GET':
                $obj = new ProductController();
                $obj->getCatalog();

                require_once './../View/nav.html';
                break;

            default:
                echo "метод $requestMethod не поддерживается для запроса $requestUri";
                break;
        }

        break;

    case '/addProduct':
        require_once './../Controller/ProductController.php';

        switch ($requestMethod)
        {
            case 'POST':
                $obj = new ProductController();
                $obj->addProduct();
                header('Location: /catalog');
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