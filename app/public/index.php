<?php

use Controller\CartController;use Controller\OrderController;use Controller\ProductController;use Controller\UserController;
use Kurbanali\MyCore\Autoloader;
use Kurbanali\MyCore\App;
use Kurbanali\MyCore\Container\Container;

require_once './../vendor/autoload.php';

Autoloader::registrate();

$services = include './../Config/services.php';

$container = new Container($services);

$app = new App($container);

$app->get('/registrate',UserController::class, 'getRegistrate');
$app->post('/registrate',UserController::class, 'postRegistrate', \Request\RegistrateRequest::class);

$app->get('/login',UserController::class, 'getLogin');
$app->post('/login',UserController::class, 'postLogin', \Request\LoginRequest::class);

$app->get('/logout',UserController::class, 'logout');
$app->get('/catalog',ProductController::class, 'getCatalog');
$app->get('/cart',CartController::class, 'getCart');

$app->post('/product-plus',CartController::class, 'plus', \Request\PlusRequest::class);
$app->post('/product-minus',CartController::class, 'minus', \Request\MinusRequest::class);

$app->post('/order', OrderController::class, 'checkout', \Request\OrderRequest::class);

$app->get('/api/users', \Controller\Api\UserController::class, 'index');
$app->post('/api/users', \Controller\Api\UserController::class, 'create', \Request\Api\CreateUserRequest::class);

$app->run();





