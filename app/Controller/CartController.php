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

    public function __construct(SessionAuthenticationService $authenticationService, CartService $cartService)
    {
        $this->authenticationService = $authenticationService;
        $this->cartService = $cartService;

    }

    public function getCart(): void
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();

        $userProducts = UserProduct::getCartItems($userId);

        $products = $this->cartService->getCart($userProducts);

        $cartQuantity = UserProduct::getCartQuantity($userId);;

        $totalPrice = 0;

        require_once './../View/cart.phtml';
    }

    public function plus(PlusRequest $request): void
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }

        $errors = $request->validate();

        if (empty($errors)) {

            $this->cartService->plus($request->getProductId(), $user);

            header("Location: /catalog");
        }

    }

    public function minus(MinusRequest $request): void
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }

        $errors = $request->validate();

        if (empty($errors)) {
            $this->cartService->minus($request->getProductId(), $user);

            header("Location: /catalog");
        }
    }

}