<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\Request;
use Service\SessionService;
class ProductController
{
    public function getCatalog()
    {
        SessionService::requireLoggedInUser();
        $userId = $_SESSION['user_id'];

        $products = Product::getAll();
        $cartQuantity = self::getCartQuantity($userId);

        require_once './../View/catalog.phtml';
    }
    public static function getCartQuantity($userId) : int|null
    {
        return Product::getCartQuantity($userId);
    }

    public function getCart()
    {

        SessionService::requireLoggedInUser();

        $userId = $_SESSION['user_id'];

        $products = Product::getAllByUserId($userId);
        $cartQuantity = self::getCartQuantity($userId);

        require_once './../View/cart.phtml';
    }
    public function plus(Request $request)
    {
        SessionService::requireLoggedInUser();

        $userId = $_SESSION['user_id'];
        $productId = $request->getOneByKey('product_id');

        UserProduct::updateOrCreate($userId, $productId);
        header("Location: /catalog");

    }
    public function minus(Request $request)
    {
        SessionService::requireLoggedInUser();

        $userId = $_SESSION['user_id'];
        $productId = $request->getOneByKey('product_id');

        UserProduct::updateOrDelete($userId, $productId);
        header("Location: /catalog");

    }

}