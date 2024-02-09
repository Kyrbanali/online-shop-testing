<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\Request;
use Service\SessionAuthenticationService;
class ProductController
{
    private SessionAuthenticationService $authenticationService;
    public function __construct()
    {
        $this->authenticationService = new SessionAuthenticationService();
    }
    public function getCatalog()
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();

        $products = Product::getAll();
        $cartQuantity = UserProduct::getCartQuantity($userId);;

        require_once './../View/catalog.phtml';
    }


}