<?php

namespace Service;

use Model\User;

class CartService
{
    public function plus(int $productId, User $user)
    {
        $userId = $user->getId();

    }

    public function minus()
    {

    }

}