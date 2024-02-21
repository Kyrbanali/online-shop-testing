<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Service\Authentication\AuthenticationServiceInterface;

class ProductController
{
    private AuthenticationServiceInterface $authenticationService;
    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function getCatalog(): array
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();

        return [
            'view' => 'catalog.phtml',
            'params' => [
                'products' => Product::getAll(),
                'cartQuantity' => UserProduct::getCartQuantity($userId),
            ],

        ];
    }
}