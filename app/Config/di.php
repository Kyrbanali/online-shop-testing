<?php
use Core\Container\Container;
use Service\Authentication\AuthenticationServiceInterface;
use Service\Authentication\SessionAuthenticationServiceService;
use Service\OrderService;
use Controller\CartController;
use Controller\OrderController;
use Controller\ProductController;
use Controller\UserController;

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