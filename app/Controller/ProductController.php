<?php

class ProductController
{
    public function getCatalog()
    {
        session_start();
        if (!isset($_SESSION['user_id']))
        {
            header('Location: /login');
        }

        $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbtest", "dbuser", "dbpwd");

        $stmt = $pdo->query('SELECT * FROM products');
        $products = $stmt->fetchAll();


        require_once './../View/catalog.phtml';
    }

    public function addProduct()
    {
        session_start();
        if (!isset($_SESSION['user_id']))
        {
            header('Location: /login');
        }

        $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbtest", "dbuser", "dbpwd");

        $stmt = $pdo->prepare('INSERT INTO user_products (user_id, product_id) VALUES (:user_id, :product_id)');
        $stmt->execute(['user_id' => $_SESSION['user_id'], 'product_id' => $_POST['product_id']]);
    }
}