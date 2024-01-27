<?php


class UserProduct extends Model
{
    public function create(int $userId, int $productId, int $quantity) : void
    {

        $sql = 'INSERT INTO user_products (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId, 'quantity' => $quantity]);
    }

    public function delete(int $userId, int $productId) : void
    {
        $sql = "DELETE FROM user_products WHERE user_id='$userId' and product_id='$productId'";

        $this->pdo->exec($sql);
    }

    //
}