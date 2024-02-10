<?php
namespace Model;
class UserProduct extends Model
{
    private int $id;
    private int $user_id;
    private int $product_id;
    private int $quantity;

    public function setQuantity(int $quantity) : void
    {
        $this->quantity = $quantity;

    }
    public function getId(): int
    {
        return $this->id;
    }
    public function getUserId(): int
    {
        return $this->user_id;
    }
    public function getProductId(): int
    {
        return $this->product_id;
    }
    public static function getCart(int $userId): ?array
    {
        $sql = <<<SQL
            SELECT * 
            FROM user_products
            WHERE user_id = :user_id
        SQL;

        $data = ['user_id' => $userId];

        $stmt = self::prepareExecute($sql, $data);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }
    public static function getCartQuantity(int $userId) : ?int
    {
        $sql = <<<SQL
            SELECT SUM(user_products.quantity) as total_quantity
            FROM users
            JOIN user_products ON users.id = user_products.user_id
            WHERE users.id = :user_id
            GROUP BY users.id;
        SQL;

        $data = ['user_id' => $userId];

        $stmt = self::prepareExecute($sql, $data);

        $result = $stmt->fetch();
        return $result['total_quantity'] ?? null;
    }
    public function save(int $quantity, int $userId, int $productId)
    {
        $sql = <<<SQL
                UPDATE user_products
                SET quantity = :quantity
                WHERE product_id = :product_id AND user_id = :user_id;
        SQL;

        $data = ['quantity' => $quantity, 'user_id' => $userId, 'product_id' => $productId];

        self::prepareExecute($sql, $data);
    }
    public static function updateOrCreate(int $userId, int $productId) : void
    {
        $recordExists = self::recordExists($userId, $productId);

        if ($recordExists)
        {

            $sql = "UPDATE user_products SET quantity = quantity + 1 WHERE user_id = :user_id AND product_id = :product_id";
            $data = ['user_id' => $userId, 'product_id' => $productId];

            self::prepareExecute($sql, $data);
        }
        else
        {

            $sql = "INSERT INTO user_products (user_id, product_id, quantity) VALUES (:user_id, :product_id, 1)";

            $data = ['user_id' => $userId, 'product_id' => $productId];

            self::prepareExecute($sql, $data);

        }
    }
    public static function updateOrDelete(int $userId, int $productId) : void
    {
        $recordExists = self::recordExists($userId, $productId);
        $quantity = self::getQuantity($userId, $productId);

        if ($recordExists)
        {
            if ($quantity > 1)
            {
                $sql = "UPDATE user_products SET quantity = quantity - 1 WHERE user_id = :user_id AND product_id = :product_id";

                $data = ['user_id' => $userId, 'product_id' => $productId];

                self::prepareExecute($sql, $data);
            }
            elseif ($quantity === 1)
            {

                $sql = "DELETE FROM user_products WHERE user_id = :user_id AND product_id = :product_id";

                $data = ['user_id' => $userId, 'product_id' => $productId];

                self::prepareExecute($sql, $data);
            }
        }
        else
        {
            //здесь типа обработка удаления пустоты может быть, но вряд ли
        }
    }
    public static function getQuantity(int $userId, int $productId)
    {
        $sql = "SELECT quantity FROM user_products WHERE user_id = :user_id AND product_id = :product_id";


        $data = ['user_id' => $userId, 'product_id' => $productId];

        $stmt = self::prepareExecute($sql, $data);

        $quantity = $stmt->fetchColumn();

        return $quantity;

    }
    public static function recordExists(int $userId, int $productId) : bool
    {
        $sql = "SELECT COUNT(*) FROM user_products WHERE user_id = :user_id AND product_id = :product_id";

        $data = ['user_id' => $userId, 'product_id' => $productId];

        $stmt = self::prepareExecute($sql, $data);
        $count = $stmt->fetchColumn();

        return $count > 0;
    }
}