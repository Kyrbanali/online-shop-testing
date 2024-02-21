<?php

namespace Service;

use Model\Order;
use Model\OrderItem;
use Model\UserProduct;
use Service\Logger\LoggerService;

class OrderService
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create(int $userId, string $phone, string $address): bool
    {
        $cartItems = UserProduct::getCartItems($userId);

        $this->pdo->beginTransaction();


        try {
            $orderId = Order::create($userId, $phone, $address);

            foreach ($cartItems as $cartItem) {
                OrderItem::create($orderId, $cartItem->getProductId(), $cartItem->getQuantity());
            }

            $this->pdo->commit();

            return true;

        } catch (\Throwable $exception) {
            $file = $exception->getFile();
            $line = $exception->getLine();
            $message = $exception->getMessage();

            LoggerService::error($file, $line, $message);

            $this->pdo->rollBack();
        }

        return false;
    }
}