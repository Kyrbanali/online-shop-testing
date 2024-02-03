<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
use Request\Request;
use Service\SessionService;
class ProductController
{

    private Product $product;
    private UserProduct $userProduct;

    public function __construct()
    {
        $this->product = new Product();
        $this->userProduct = new UserProduct();
    }
    public function getCatalog()
    {
        SessionService::requireLoggedInUser();
        $userId = $_SESSION['user_id'];

        $products = $this->product->getAll();
        $result = $this->product->getCartQuantity($userId);

        require_once './../View/catalog.phtml';
    }

    public function getCart()
    {

        SessionService::requireLoggedInUser();

        $userId = $_SESSION['user_id'];

        $products = $this->product->getAllFromCart($userId);
        $result = $this->product->getCartQuantity($userId);

        require_once './../View/cart.phtml';
    }
    public function plus(Request $request)
    {
        SessionService::requireLoggedInUser();

        $userId = $_SESSION['user_id'];
        $productId = $request->getOneByKey('product_id');

        $this->userProduct->updateOrCreate($userId, $productId);
        header("Location: /catalog");

    }
    public function minus(Request $request)
    {
        SessionService::requireLoggedInUser();

        $userId = $_SESSION['user_id'];
        $productId = $request->getOneByKey('product_id');

        $this->userProduct->updateOrDelete($userId, $productId);
        header("Location: /catalog");

    }

}