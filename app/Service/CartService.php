<?php

namespace Service;

use Model\Product;
use Model\User;
use Model\UserProduct;

class CartService
{
    public function getCart(array $userProducts): ?array
    {
        if (!empty($userProducts)) {
            foreach ($userProducts as $userProduct) {
                $productIds[] = $userProduct->getProductId();
            }

            if (!empty($productIds)) {
                $products = Product::getAllByIds($productIds);
            }
        }

        return $products ?? null;
    }

    public function plus(int $productId, User $user): void
    {
        $userId = $user->getId();

        UserProduct::updateOrCreate($userId, $productId);
    }

    public function minus(int $productId, User $user): void
    {
        $userId = $user->getId();
        $userProduct = UserProduct::getOneByUserIdProductId($userId, $productId);

        $userProduct->updateOrDelete($userId, $productId);
    }

}