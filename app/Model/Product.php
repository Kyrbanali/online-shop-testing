<?php

require_once 'Model.php';
class Product extends Model
{

    public function getAll() : array
    {

        $stmt = $this->pdo->query('SELECT * FROM products');
        return $stmt->fetchAll();
    }

    public function getOne() : mixed
    {

        $stmt = $this->pdo->query('SELECT * FROM products LIMIT 1');
        return $stmt->fetch();
    }

}