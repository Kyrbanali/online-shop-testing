<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\MinusRequest;
use Request\PlusRequest;
use Service\CartService;
use Service\SessionAuthenticationService;

class CartController
{
    private SessionAuthenticationService $authenticationService;
    private CartService $cartService;

    public function __construct(SessionAuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;

    }

    public function getCart()
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();

        $userProducts = UserProduct::getCartItems($userId);


        if (!empty($userProducts)) {
            foreach ($userProducts as $userProduct) {
                $productIds[] = $userProduct->getProductId();
            }

            if (!empty($productIds)) {
                $products = Product::getAllByIds($productIds);
            }
        }

        $cartQuantity = UserProduct::getCartQuantity($userId);;

        $totalPrice = 0;

        require_once './../View/cart.phtml';
    }

    public function plus(PlusRequest $request)
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }

        //$this->cartService->plus($request->getProductId(), $user);

        $userId = $user->getId();
        $errors = $request->validate();
        if (empty($errors)) {

            $productId = $request->getProductId();

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

        //$this->cartService->minus();

        $userId = $user->getId();
        $errors = $request->validate();
        if (empty($errors)) {
            $userProduct = UserProduct::getOneByUserIdProductId($userId, $request->getProductId());

            $productId = $request->getProductId();

            $userProduct->updateOrDelete($userId, $productId);

            header("Location: /catalog");
        }
    }

}