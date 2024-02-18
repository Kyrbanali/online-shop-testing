<?php

namespace Model;

use http\Encoding\Stream\Inflate;

class Order extends Model
{
    private int $id;
    private int $userId;
    private string $phone;
    private string $address;
    private string $orderDate;
    private string $orderNumber;

    public function __construct(int $id, int $userId, string $phone, string $address, string $orderDate, string $orderNumber)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->phone = $phone;
        $this->address = $address;
        $this->orderDate = $orderDate;
        $this->orderNumber = $orderNumber;

    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getOrderDate(): string
    {
        return $this->orderDate;
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public static function create(int $userId, string $phone, string $address): int
    {
        $orderNumber = self::generateOrderNumber();
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
        return $stmt->fetchColumn();
    }

    public static function getOneByUserId(int $userId): ?Order
    {
        $sql = <<<SQL
            select * from orders 
            where user_id = :user_Id
        SQL;

        $prepare = [
            'user_id' => $userId,
        ];

        $stmt = self::prepareExecute($sql, $prepare);
        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }

        return new Order($data['id'], $data['user_id'], $data['phone'], $data['address'], $data['order_date'], $data['order_number']);
    }

    private static function generateOrderNumber(): string
    {
        return date('YmdHis') . mt_rand(1000, 9999);
    }

}