<?php


class ProductController
{
    private Product $product;

    private UserProduct $userProduct;

    public function __construct()
    {
        require_once './../Model/Product.php';
        require_once './../Model/UserProduct.php';

        $this->product = new Product();
        $this->userProduct = new UserProduct();
    }
    public function getCatalog()
    {
        session_start();
        if (!isset($_SESSION['user_id']))
        {
            header('Location: /login');
        }



        $products = $this->product->getAll();


        require_once './../View/catalog.phtml';
    }

    public function addProduct()
    {
        session_start();
        if (!isset($_SESSION['user_id']))
        {
            header('Location: /login');
        }

        $errors = $this->validate($_POST);

        if (empty($errors))
        {
            $userId = $_SESSION['user_id'];
            $productId = $_POST['product_id'];
            $quantity = $_POST['quantity'];


            $this->userProduct->create($userId, $productId, $quantity);
        }
        else {
            //require_once './../View/catalog.phtml';
        }

    }

    public function getCartProducts()
    {

    }
    private function validate(array $data): array
    {
        $errors = [];

        if (!isset($data['product_id']))
        {
            $errors['product'] = 'product is empty';
        }

        if (isset($data['quantity']))
        {
            $quantity = $data['quantity'];
            if ($quantity < 1)
            {
                $errors['quantity'] = "there must be at least 1 product";
            }
        } else {
            $errors['quantity'] = 'quantity is empty';
        }

        return $errors;
    }

}