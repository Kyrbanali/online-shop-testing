<?php

use Core\App;
use Core\Autoloader;

require_once './../Core/Autoloader.php';

Autoloader::registrate();

$app = new App();
$app->handleRequest();



