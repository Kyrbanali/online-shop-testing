<?php

namespace Service;

use Model\Model;
use Model\Order;
use Model\OrderItem;
use Model\User;
use Model\UserProduct;

class OrderService
{
    public function create(int $userId, string $phone, string $address)
    {
        $pdo = Model::getPDO();
        $pdo->beginTransaction();

        try {
            $orderId = Order::create($userId, $phone, $address);

            $cartItems = UserProduct::getCartItems($userId);

            foreach ($cartItems as $cartItem) {

                OrderItem::create($orderId, $cartItem->getProductId(), $cartItem->getQuantity());

            }

        } catch (\Throwable $exception) {
            $pdo->rollBack();
        }
    }
}