<?php

namespace Model;

class OrderModel extends Model
{
    public function create($userId, $phone, $address, $cartItems)
    {
        $orderNumber = $this->generateOrderNumber();
        $orderDate = date('Y-m-d H:i:s');

        $sql = <<<SQL
            insert into orders (user_id, phone, address, order_date, order_number)
            values (:user_id, :phone, :address, :order_date, :order_number)
            returning id;
        SQL;

        $data = [
            'user_id' => $userId,
            'phone' => $phone,
            'address' => $address,
            'order_date' => $orderDate,
            'order_number' => $orderNumber
            ];

        $stmt = self::prepareExecute($sql, $data);
        $orderId = $stmt->fetchColumn();

        $this->createOrderItems($orderId ,$cartItems);

    }

    public function createOrderItems($orderId ,$cartItems)
    {
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