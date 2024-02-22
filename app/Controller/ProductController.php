<?php

namespace Controller;

use Core\ViewRenderer;
use Model\Product;
use Model\UserProduct;
use Service\Authentication\AuthenticationServiceInterface;

class ProductController
{
    private AuthenticationServiceInterface $authenticationService;
    private ViewRenderer $renderer;

    public function __construct(AuthenticationServiceInterface $authenticationService, ViewRenderer $renderer)
    {
        $this->authenticationService = $authenticationService;
        $this->renderer = $renderer;
    }

    public function getCatalog(): string
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();

        return $this->renderer->render('catalog.phtml', [
            'products' => Product::getAll(),
            'cartQuantity' => UserProduct::getCartQuantity($userId),
        ]);
    }
}