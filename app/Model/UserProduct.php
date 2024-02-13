<?php
namespace Model;
class UserProduct extends Model
{
    private int $id;
    private int $user_id;
    private int $product_id;
    private int $quantity;

    public function __construct(int $id, int $user_id, int $product_id, int $quantity)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->product_id = $product_id;
        $this->quantity = $quantity;
    }

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

    public function getQuantity()
    {
        return $this->quantity;
    }

    public static function getCartItems(int $userId): ?array
    {
        $sql = <<<SQL
            SELECT * 
            FROM user_products
            WHERE user_id = :user_id
        SQL;

        $data = ['user_id' => $userId];

        $stmt = self::prepareExecute($sql, $data);
        $cart = $stmt->fetchAll();


        $userProducts = self::hydrate($cart);

        if (empty($userProducts)) {
            return null;
        }

        return $userProducts;
    }

    public static function getOneByUserIdProductId(int $userId, int $productId)
    {
        $sql = <<<SQL
        SELECT * FROM user_products
        WHERE user_id = :user_id AND product_id = :product_id
        SQL;

        $prepare = [
            'user_id' => $userId,
            'product_id' => $productId
        ];

        $stmt = self::prepareExecute($sql, $prepare);
        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }

        return new UserProduct($data['id'], $data['user_id'], $data['product_id'], $data['quantity']);


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

        $data = [
            'user_id' => $userId
        ];

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

    public function updateOrDelete(int $userId, int $productId) : void
    {
        $recordExists = self::recordExists($userId, $productId);
        $quantity = $this->getQuantity();

        if ($recordExists)
        {
            if ($quantity > 1) {

                $sql = <<<SQL
                UPDATE user_products 
                SET quantity = quantity - 1 
                WHERE user_id = :user_id AND product_id = :product_id;
                SQL;

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

    public static function recordExists(int $userId, int $productId) : bool
    {
        $sql = "SELECT COUNT(*) FROM user_products WHERE user_id = :user_id AND product_id = :product_id";

        $data = ['user_id' => $userId, 'product_id' => $productId];

        $stmt = self::prepareExecute($sql, $data);
        $count = $stmt->fetchColumn();

        return $count > 0;
    }

    private static function hydrate(array $products, bool $associative = false): ?array
    {
        $data = [];

        foreach ($products as $product) {

            $data[] = new UserProduct(
                $product['id'],
                $product['user_id'],
                $product['product_id'],
                $product['quantity'],
            );
        }

        return $data;
    }
}