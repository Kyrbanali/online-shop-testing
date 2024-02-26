<?php

namespace Model;

use Kurbanali\MyCore\Model\Model;

class OrderItem extends Model
{
    private int $id;
    private int $orderId;
    private int $productId;
    private int $quantity;

    public function __construct(int $id, int $orderId, int $productId, int $quantity)
    {
        $this->id = $id;
        $this->orderId  = $orderId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderId(): int
    {
        return $this->orderId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public static function create($orderId, $productId, $quantity)
    {
        $sql = <<<SQL
        insert into order_items (order_id, product_id, quantity)
        values (:order_id, :product_id, :quantity)
        SQL;

        $data = [
            'order_id' => $orderId,
            'product_id' => $productId,
            'quantity' => $quantity
        ];

        self::prepareExecute($sql, $data);
    }

}