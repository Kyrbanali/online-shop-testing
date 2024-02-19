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
        $cartItems = UserProduct::getCartItems($userId);

        $pdo = Model::getPDO();

        $pdo->beginTransaction();

        try {
            $orderId = Order::create($userId, $phone, $address);

            foreach ($cartItems as $cartItem) {
                OrderItem::create($orderId, $cartItem->getProductId(), $cartItem->getQuantity());
            }

            $pdo->commit();

        } catch (\Throwable $exception) {
            $file = $exception->getFile();
            $line = $exception->getLine();
            $message = $exception->getMessage();

            LoggerService::error($file, $line, $message);

            $pdo->rollBack();
        }
    }
}