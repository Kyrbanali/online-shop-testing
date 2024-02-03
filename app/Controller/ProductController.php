<?php

namespace Controller;

use Model\Product;
use Model\UserProduct;
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

    public function processCart() : void
    {
        SessionService::requireLoggedInUser();

        $errors = self::validate($_POST);


        if (empty($errors))
        {

            $userId = $_SESSION['user_id'];
            $productId = $_POST['product_id'];
            $action = $_POST['action'];

            switch ($action)
            {
                case 'inc':

                    $this->userProduct->updateOrCreate($userId, $productId);
                    header("Location: /catalog");

                    break;

                case 'dec':
                    $this->userProduct->updateOrDelete($userId, $productId);
                    header("Location: /catalog");

                    break;

            }
        }

    }



    private static function validate(array $data): array
    {
        $errors = [];

        if (!isset($data['product_id']))
        {
            $errors['product'] = 'product is empty';
        }

        return $errors;
    }

}