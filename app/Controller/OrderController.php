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

        $phone = $request->getPhone();
        $address = $request->getAddress();

        if (!empty($cartItems)) {
            $this->orderModel->create($phone, $address, $cartItems);
        }





    }


}