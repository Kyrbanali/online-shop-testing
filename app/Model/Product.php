<?php
namespace Model;
class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private float $price;
    private string $img_url;

    public function __construct(int $id, string $name, string $description, float $price, string $img_url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->img_url = $img_url;

    }

    public static function getAll() : ?array
    {
        $sql = 'SELECT * FROM products';

        $stmt = self::getPDO()->query($sql);
        $products = $stmt->fetchAll();

        foreach ($products as $product) {
            $data[] = new Product($product['id'], $product['name'], $product['description'], $product['price'], $product['img_url']);
        }

        if (empty($data)) {
            return null;
        }
        return $data;
    }

    public static function getAllByIds(array $productIds) : ?array
    {
        $string = implode(", ", $productIds);
        $sql = <<<SQL
                SELECT * FROM products WHERE id IN ($string)
                SQL;
        $stmt = self::getPdo()->query($sql);
        $products = $stmt->fetchAll();

        foreach ($products as $product) {
            $data[$product['id']] = new Product($product['id'], $product['name'], $product['description'], $product['price'], $product['img_url']);
        }

        if (empty($data)) {
            return null;
        }

        return $data;
    }

    public function getOneById(int $productId)
    {
        $sql = 'SELECT * FROM products where id = :product_id';

        $data = ['product_id' => $productId];

        $stmt = self::prepareExecute($sql, $data);
        $result = $stmt->fetch();
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