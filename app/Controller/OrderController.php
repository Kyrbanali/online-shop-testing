<?php

namespace Controller;

use Model\OrderModel;
use Model\UserProduct;
use Request\OrderRequest;
use Service\Authentication\AuthenticationServiceInterface;

class OrderController
{
    private AuthenticationServiceInterface $authenticationService;
    private OrderModel $orderModel;

    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
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