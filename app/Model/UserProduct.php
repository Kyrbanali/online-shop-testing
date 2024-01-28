<?php
namespace Model;
class UserProduct extends Model
{
    private function prepareExecute(int $userId, int $productId, $sql)
    {

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);

        return $stmt;
    }
    private function recordExists(int $userId, int $productId) : bool
    {

        $checkSql = "SELECT COUNT(*) FROM user_products WHERE user_id = :user_id AND product_id = :product_id";

        $stmt = $this->prepareExecute($userId, $productId, $checkSql);
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    public function getQuantity(int $userId, int $productId)
    {
        $getQuantitySql = "SELECT quantity FROM user_products WHERE user_id = :user_id AND product_id = :product_id";

        $getQuantityStmt = $this->prepareExecute($userId, $productId, $getQuantitySql);

        $quantity = $getQuantityStmt->fetchColumn();

        return $quantity;

    }
    public function updateOrCreate(int $userId, int $productId) : void
    {
        $recordExists = $this->recordExists($userId, $productId);

        if ($recordExists)
        {

            $updateSql = "UPDATE user_products SET quantity = quantity + 1 WHERE user_id = :user_id AND product_id = :product_id";

            $this->prepareExecute($userId, $productId, $updateSql);
        }
        else
        {

            $insertSql = "INSERT INTO user_products (user_id, product_id, quantity) VALUES (:user_id, :product_id, 1)";

            $this->prepareExecute($userId, $productId, $insertSql);

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

                $this->prepareExecute($userId, $productId, $updateSql);
            }
            elseif ($quantity === 1)
            {

                $deleteSql = "DELETE FROM user_products WHERE user_id = :user_id AND product_id = :product_id";

                $this->prepareExecute($userId, $productId, $deleteSql);
            }
        }
        else
        {
            //здесь типа обработка удаления пустоты может быть, но вряд ли
        }
    }
}