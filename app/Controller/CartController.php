<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
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
    public function plus(Request $request)
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();
        $productId = $request->getOneByKey('product_id');

        UserProduct::updateOrCreate($userId, $productId);
        header("Location: /catalog");

    }
    public function minus(Request $request)
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();
        $productId = $request->getOneByKey('product_id');

        UserProduct::updateOrDelete($userId, $productId);
        header("Location: /catalog");
    }

}