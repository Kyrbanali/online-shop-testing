<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\MinusRequest;
use Request\PlusRequest;

class CartController extends BaseController
{
    public function getCart(): string
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

        return $this->renderer->render('cart.phtml', [
            'userProducts' => UserProduct::getCartItems($userId),
            'products' => Product::getAllByIds($productIds),
            'totalPrice' => 0,
            'cartQuantity' => UserProduct::getCartQuantity($userId),
        ]);
    }

    public function plus(PlusRequest $request): void
    {
        $user = $this->authenticationService->getCurrentUser();
        if (!$user) {
            header("Location: /login");
        }

        $errors = $request->validate();

        if (empty($errors)) {

            $userId = $user->getId();

            UserProduct::updateOrCreate($userId, $request->getProductId());

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
            $userId = $user->getId();
            $productId = $request->getProductId();

            $userProduct = UserProduct::getOneByUserIdProductId($userId, $productId);
            $userProduct->updateOrDelete($userId, $productId);

            header("Location: /catalog");
        }
    }

}