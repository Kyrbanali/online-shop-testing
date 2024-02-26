<?php
namespace Model;

use Kurbanali\MyCore\Model\Model;

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

    public static function getAll() : ?array
    {
        $sql = 'SELECT * FROM products';

        $stmt = self::getPDO()->query($sql);
        $products = $stmt->fetchAll();

        $data = self::hydrate($products);


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

        $data = self::hydrate($products, true);

        if (empty($data)) {
            return null;
        }

        return $data;
    }

    public function getOneById(int $productId): ?Product
    {
        $sql = 'SELECT * FROM products where id = :product_id';

        $data = ['product_id' => $productId];

        $stmt = self::prepareExecute($sql, $data);
        $result = $stmt->fetch();

        if (empty($result)) {
            return null;
        }

        return new Product($result['id'], $result['name'], $result['description'], $result['price'], $result['img_url']);
    }

    public static function hydrate(array $products, bool $associative = false): ?array
    {
        $data = [];

        foreach ($products as $product) {
            if ($associative) {
                $data[$product['id']] = new Product(
                    $product['id'],
                    $product['name'],
                    $product['description'],
                    $product['price'],
                    $product['img_url']
                );
            } else {
                $data[] = new Product(
                    $product['id'],
                    $product['name'],
                    $product['description'],
                    $product['price'],
                    $product['img_url']
                );
            }
        }

        return $data;
    }


}