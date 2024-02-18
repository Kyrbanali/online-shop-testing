<?php

namespace Model;

class OrderItem extends Model
{
    private int $id;
    private int $orderId;
    private int $productId;
    private int $quantity;

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