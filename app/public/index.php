<?php


$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

$autoloader = function (string $class) : bool
{
    $prefixes = [
        'Controller' => './../Controller/',
        'Model' => './../Model/',
        'Service' => './../Service/'
    ];

    foreach ($prefixes as $namespace => $path)
    {
        $file = $path . str_replace('\\','/',substr($class, strlen($namespace)) . '.php');

        if (file_exists($file))
        {
            require_once $file;
            return true;
        }
        return false;
    }

};

$controllerAutoloader = function (string $class)
{
    $path = "./../Controller/$class.php";
    if (file_exists($path))
    {
        require_once $path;

        return true;
    }
    return false;
};

$modelAutoloader = function (string $class)
{
    $path = "./../Model/$class.php";
    if (file_exists($path))
    {
        require_once $path;

        return true;
    }
    return false;
};

$serviceAutoloader = function (string $class)
{
    $path = "./../Service/$class.php";
    if (file_exists($path))
    {
        require_once $path;

        return true;
    }
    return false;
};

spl_autoload_register($controllerAutoloader);
spl_autoload_register($modelAutoloader);
spl_autoload_register($serviceAutoloader);

//spl_autoload_register($autoloader);


switch ($requestUri)
{
    case '/registrate':

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
                $obj = new ProductController();
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
                $obj = new ProductController();
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