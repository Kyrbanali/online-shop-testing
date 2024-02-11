<?php

namespace Controller;

use Request\OrderRequest;
use Service\SessionAuthenticationService;

class OrderController
{
    private SessionAuthenticationService $authenticationService;

    public function __construct()
    {
        $this->authenticationService = new SessionAuthenticationService();
    }

    public function getOrder(OrderRequest $request): void
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();

    }

    public function postOrder(OrderRequest $request): void
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }
        $userId = $user->getId();

    }

}