<?php

use Core\App;
use Core\Autoloader;
use Controller\ProductController;
use Controller\UserController;
use Controller\CartController;
use Controller\OrderController;
use Service\Authentication\SessionAuthenticationServiceService;
use Service\OrderService;
use Core\Container;
use Service\Authentication\AuthenticationServiceInterface;

require_once './../Core/Autoloader.php';

Autoloader::registrate();

$container = new Container();

$container->set(AuthenticationServiceInterface::class, function () {
    return new SessionAuthenticationServiceService();
});

$container->set(OrderService::class, function () {
    return new OrderService();
});

$container->set(UserController::class, function (Container $container) {
    $authenticationService = $container->get(AuthenticationServiceInterface::class);

    return new UserController($authenticationService);
});

$container->set(ProductController::class, function (Container $container) {
    $authenticationService = $container->get(AuthenticationServiceInterface::class);

    return new ProductController($authenticationService);
});

$container->set(CartController::class, function (Container $container) {
    $authenticationService = $container->get(AuthenticationServiceInterface::class);

    return new CartController($authenticationService);
});

$container->set(OrderController::class, function (Container $container) {
    $authenticationService = $container->get(AuthenticationServiceInterface::class);
    $orderService = $container->get(OrderService::class);

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





