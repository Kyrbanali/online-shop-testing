<?php

use Core\App;
use Core\Autoloader;
use Controller\ProductController;
use Controller\UserController;
use Controller\CartController;
use Controller\OrderController;

require_once './../Core/Autoloader.php';

Autoloader::registrate();

$app = new App();

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





