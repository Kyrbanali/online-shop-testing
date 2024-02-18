<?php

use Core\App;
use Core\Autoloader;
use Controller\ProductController;
use Controller\UserController;
use Controller\CartController;
use Controller\OrderController;

require_once './../Core/Autoloader.php';

Autoloader::registrate();

$container = new \Core\Container();

$container->set(UserController::class, function () {
    $authenticationService = new \Service\Authentication\SessionAuthenticationServiceService();

    return new UserController($authenticationService);
});

$container->set(ProductController::class, function () {
    $authenticationService = new \Service\Authentication\SessionAuthenticationServiceService();

    return new ProductController($authenticationService);
});

$container->set(CartController::class, function () {
    $authenticationService = new \Service\Authentication\SessionAuthenticationServiceService();

    return new CartController($authenticationService);
});

$container->set(OrderController::class, function () {
    $authenticationService = new \Service\Authentication\SessionAuthenticationServiceService();
    $orderService = new \Service\OrderService();

    return new OrderController($authenticationService, $orderService);
});

$app = new App($container);

$app->get('/registrate',UserController::class, 'getRegistrate');
$app->post('/registrate',UserController::class, 'postRegistrate', \Request\RegistrateRequest::class);

$app->get('/login',UserController::class, 'getLogin', );
$app->post('/login',UserController::class, 'postLogin', \Request\LoginRequest::class);

$app->get('/logout',UserController::class, 'logout');
$app->get('/catalog',ProductController::class, 'getCatalog');
$app->get('/cart',CartController::class, 'getCart');

$app->post('/product-plus',CartController::class, 'plus', \Request\PlusRequest::class);
$app->post('/product-minus',CartController::class, 'minus', \Request\MinusRequest::class);

$app->post('/order', OrderController::class, 'checkout', \Request\OrderRequest::class);


$app->handleRequest();





