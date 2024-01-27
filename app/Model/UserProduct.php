<?php

class UserProduct extends Model
{

    private function recordExists(int $userId, int $productId) : bool
    {
        $checkSql = "SELECT 1 FROM user_products WHERE user_id = :user_id AND product_id = :product_id";
        $checkStmt = $this->pdo->prepare($checkSql);
        $checkStmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $recordExists = $checkStmt->fetchColumn();

        return $recordExists ? true : false;

    }

    private function getQuantity(int $userId, int $productId)
    {
        $getQuantitySql = "SELECT quantity FROM user_products WHERE user_id = :user_id AND product_id = :product_id";
        $getQuantityStmt = $this->pdo->prepare($getQuantitySql);
        $getQuantityStmt->execute(['user_id' => $userId, 'product_id' => $productId]);

        $quantity = $getQuantityStmt->fetchColumn();

        return $quantity;

    }
    public function updateOrCreate(int $userId, int $productId) : void
    {

        $recordExists = $this->recordExists($userId, $productId);

        if ($recordExists)
        {
            $updateSql = "UPDATE user_products SET quantity = quantity + 1 WHERE user_id = :user_id AND product_id = :product_id";
            $updateStmt = $this->pdo->prepare($updateSql);
            $updateStmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        }
        else {
            $insertSql = "INSERT INTO user_products (user_id, product_id, quantity) VALUES (:user_id, :product_id, 1)";
            $insertStmt = $this->pdo->prepare($insertSql);
            $insertStmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        }
    }

    public function updateOrDelete(int $userId, int $productId) : void
    {
        $recordExists = $this->recordExists($userId, $productId);
        $quantity = $this->getQuantity($userId, $productId);

        if ($recordExists)
        {
            if ($quantity > 1)
            {
                $updateSql = "UPDATE user_products SET quantity = quantity - 1 WHERE user_id = :user_id AND product_id = :product_id";
                $updateStmt = $this->pdo->prepare($updateSql);
                $updateStmt->execute(['user_id' => $userId, 'product_id' => $productId]);
            }
            elseif ($quantity === 1)
            {

                $deleteSql = "DELETE FROM user_products WHERE user_id = :user_id AND product_id = :product_id";
                $deleteStmt = $this->pdo->prepare($deleteSql);
                $deleteStmt->execute(['user_id' => $userId, 'product_id' => $productId]);
            }
        }
        else
        {
            //здесь типа обработка удаления пустоты может быть, но вряд ли
        }
    }
}