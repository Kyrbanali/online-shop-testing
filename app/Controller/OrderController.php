<?php

namespace Controller;

use Core\ViewRenderer;
use Model\UserProduct;
use Request\OrderRequest;
use Service\Authentication\AuthenticationServiceInterface;
use Service\OrderService;

class OrderController extends BaseController
{
    private OrderService $orderService;

    public function __construct(AuthenticationServiceInterface $authenticationService, ViewRenderer $renderer, OrderService $orderService)
    {
        parent::__construct($authenticationService, $renderer);
        $this->orderService = $orderService;
    }

    public function checkout(OrderRequest $request)
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
                $result = $this->orderService->create($userId, $phone, $address);

                if (!$result) {
                    //заказ не был собран, обратитесь в службу поддержки
                }
            }
        }


    }


}