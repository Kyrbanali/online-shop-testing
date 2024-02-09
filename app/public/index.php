<?php

use Core\App;
use Core\Autoloader;
use Controller\ProductController;
use Controller\UserController;
use Controller\CartController;

require_once './../Core/Autoloader.php';

Autoloader::registrate();

$app = new App();

$app->get('/registrate',UserController::class, 'getRegistrate');
$app->post('/registrate',UserController::class, 'postRegistrate');

$app->get('/login',UserController::class, 'getLogin');
$app->post('/login',UserController::class, 'postLogin');

$app->get('/logout',UserController::class, 'logout');
$app->get('/catalog',ProductController::class, 'getCatalog');
$app->get('/cart',CartController::class, 'getCart');


$app->post('/product-plus',CartController::class, 'plus');
$app->post('/product-minus',CartController::class, 'minus');



$app->handleRequest();





