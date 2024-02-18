<?php

namespace Service;

use Model\Model;
use Model\Order;
use Model\OrderItem;
use Model\UserProduct;

class OrderService
{
    public function create(int $userId, string $phone, string $address)
    {
        $pdo = Model::getPDO();
        $pdo->beginTransaction();

        $cartItems = UserProduct::getCartItems($userId);

        try {
            $orderId = Order::create($userId, $phone, $address);

            foreach ($cartItems as $cartItem) {
                OrderItem::create($orderId, $cartItem->getProductId(), $cartItem->getQuantity());
            }

        } catch (\Throwable $exception) {
            $file = $exception->getFile();
            $line = $exception->getLine();
            $message = $exception->getMessage();

            LoggerService::error($file, $line, $message);

            $pdo->rollBack();
        }
    }
}