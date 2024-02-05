<?php
namespace Model;
class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private float $price;
    private string $img_url;

//    public function __construct(array $data)
//    {
//        $this->id = $data['id'];
//        $this->name = $data['name'];
//        $this->description = $data['description'];
//        $this->price = $data['price'];
//        $this->img_url = $data['img_url'];
//    }
    public static function getAll() : array
    {
        $sql = 'SELECT * FROM products';

        $stmt = self::getPDO()->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function getAllFromCart(int $userId) : array
    {
        $sql = "SELECT products.* FROM products
                JOIN user_products ON products.id = user_products.product_id
                JOIN users ON user_products.user_id = users.id
                WHERE users.id = :user_id;";


        $data = ['user_id' => $userId];

        $stmt = self::prepareExecute($sql, $data);
        return $stmt->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    public static function getCartQuantity(int $userId) : int | null
    {
        $sql = "
            SELECT SUM(user_products.quantity) as total_quantity
            FROM users
            JOIN user_products ON users.id = user_products.user_id
            WHERE users.id = :user_id
            GROUP BY users.id;
        ";

        $data = ['user_id' => $userId];

        $stmt = self::prepareExecute($sql, $data);

        $result = $stmt->fetch();
        return $result['total_quantity'] ?? null;
    }

    public function getOneById(int $productId) : Product
    {
        $sql = 'SELECT * FROM products where id = :product_id';

        $data = ['product_id' => $productId];

        $stmt = self::prepareExecute($sql, $data);
        $result = $stmt->fetch();

        return new Product($result);
    }
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getImgUrl(): string
    {
        return $this->img_url;
    }

    public function getQuantity(int $userId) : int
    {
        $sql = "SELECT quantity FROM user_products WHERE user_id = :user_id AND product_id = :product_id";

        $data = ['user_id' => $userId, 'product_id' => $this->id];
        $stmt = self::prepareExecute($sql, $data);

        $result = $stmt->fetch();
        return $result['quantity'];

    }


}