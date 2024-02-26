<?php


use Controller\CartController;
use Controller\OrderController;
use Controller\ProductController;
use Controller\UserController;
use Kurbanali\MyCore\AuthenticationServiceInterface;
use Kurbanali\MyCore\Container\Container;
use Kurbanali\MyCore\ViewRenderer;
use Service\Authentication\SessionAuthenticationService;
use Service\OrderService;

return [
    PDO::class => function () {
        $dbHost = getenv('DB_HOST', 'db');
        $dbName = getenv('DB_NAME', 'dbtest');
        $dbUser = getenv('DB_USER', 'dbuser');
        $dbPassword = getenv('DB_PASSWORD', 'dbpwd');

        return new PDO("pgsql:host=$dbHost;port=5432;dbname=$dbName", "$dbUser", "$dbPassword");
    },
    AuthenticationServiceInterface::class => function () {
        return new SessionAuthenticationService();
    },
    OrderService::class => function (Container $container) {
        $pdo = $container->get(PDO::class);

        return new OrderService($pdo);
    },
    ViewRenderer::class => function () {
        return new ViewRenderer();
    },
    UserController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $renderer = $container->get(ViewRenderer::class);

        return new UserController($authenticationService, $renderer);
    },
    ProductController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $renderer = $container->get(ViewRenderer::class);

        return new ProductController($authenticationService, $renderer);
    },
    CartController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $renderer = $container->get(ViewRenderer::class);

        return new CartController($authenticationService, $renderer);
    },
    OrderController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $renderer = $container->get(ViewRenderer::class);
        $orderService = $container->get(OrderService::class);

        return new OrderController($authenticationService, $renderer, $orderService);
    },
    \Controller\Api\UserController::class => function (Container $container) {
        $authenticationService = $container->get(AuthenticationServiceInterface::class);
        $renderer = $container->get(ViewRenderer::class);

        return new \Controller\Api\UserController($authenticationService, $renderer);

    }
];