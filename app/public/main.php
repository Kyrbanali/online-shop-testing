<?php



require_once './../Controller/ProductController.php';

$obj = new ProductController();
$obj->getCatalog();

require_once './../View/nav.php';



?>

