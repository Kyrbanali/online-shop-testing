<?php

class Product extends Model
{

    public function getAll() : array
    {
        $sql = 'SELECT * FROM products';

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getAllToCart($userId) : array
    {
        $sql = "SELECT products.* FROM products
                                        JOIN user_products ON products.id = user_products.product_id
                                        JOIN users ON user_products.user_id = users.id
                                        WHERE users.id = '$userId';";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll();
    }

    public function getCartInfo($userId)
    {
        $sql = "
            SELECT users.name, SUM(user_products.quantity) as total_quantity
            FROM users
            JOIN user_products ON users.id = user_products.user_id
            WHERE users.id = :user_id
            GROUP BY users.id;
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetch();
    }
    public function getOne() : mixed
    {
        $sql = 'SELECT * FROM products LIMIT 1';

        $stmt = $this->pdo->query($sql);
        return $stmt->fetch();
    }


}