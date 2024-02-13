<?php

namespace Service;

use Model\User;

class OrderService
{
    public function create(int $userId, string $phone, string $address, array $cartItems)
    {
        $orderNumber = $this->generateOrderNumber();
        $orderDate = date('Y-m-d H:i:s');

        foreach ($cartItems as $cartItem) {
            $productId = $cartItem->getProductId();
            $quantity = $cartItem->getQuantity();

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

    private function generateOrderNumber(): string
    {
        return date('YmdHis') . mt_rand(1000, 9999);
    }

}