<?php

namespace Controller;

use Model\Order;
use Model\OrderItem;
use Model\UserProduct;
use Request\OrderRequest;
use Service\Authentication\AuthenticationServiceInterface;
use Service\OrderService;

class OrderController
{
    private AuthenticationServiceInterface $authenticationService;
    private OrderService $orderService;

    public function __construct(AuthenticationServiceInterface $authenticationService, OrderService $orderService)
    {
        $this->authenticationService = $authenticationService;
        $this->orderService = $orderService;
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
                $this->orderService->create($userId, $phone, $address);
            }
        }
    }


}