<?php

namespace Controller;

use Model\OrderModel;
use Model\Product;
use Model\UserProduct;
use Request\OrderRequest;
use Service\SessionAuthenticationService;

class OrderController
{
    private SessionAuthenticationService $authenticationService;
    private OrderModel $orderModel;

    public function __construct()
    {
        $this->authenticationService = new SessionAuthenticationService();
        $this->orderModel = new OrderModel();
    }

    public function checkout(OrderRequest $request): void
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();

        $cartItems = UserProduct::getCartItems($userId);

        $errors = $request->validate();

        if (!empty($cartItems)) {
            if (empty($errors)) {
                $phone = $request->getPhone();
                $address = $request->getAddress();
                $this->orderModel->create($userId, $phone, $address, $cartItems);
            }
        }

    }


}