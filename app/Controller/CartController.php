<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\MinusRequest;
use Request\PlusRequest;
use Request\Request;
use Service\SessionAuthenticationService;

class CartController
{
    private SessionAuthenticationService $authenticationService;
    public function __construct()
    {
        $this->authenticationService = new SessionAuthenticationService();
    }

    public function getCart()
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();

        $products = Product::getAllByUserId($userId);
        $cartQuantity = UserProduct::getCartQuantity($userId);;

        require_once './../View/cart.phtml';
    }
    public function plus(PlusRequest $request)
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();
        $errors = $request->validate();
        if (empty($errors)) {

            $productId = $request->getId();

            UserProduct::updateOrCreate($userId, $productId);
            header("Location: /catalog");
        }

    }
    public function minus(MinusRequest $request)
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();
        $errors = $request->validate();
        if (empty($errors)) {

            $productId = $request->getId();

            UserProduct::updateOrDelete($userId, $productId);
            header("Location: /catalog");
        }
    }

}