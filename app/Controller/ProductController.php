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
        if (session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }
        if (!isset($_SESSION['user_id']))
        {
            header('Location: /login');
        }

        $products = $this->product->getAll();

        require_once './../View/catalog.phtml';
    }

    public function getCart()
    {

        if (session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }
        if (!isset($_SESSION['user_id']))
        {
            header('Location: /login');
        }

        $userId = $_SESSION['user_id'];

        $products = $this->product->getAllToCart($userId);

        require_once './../View/cart.phtml';
    }

    public function getCartInfo()
    {
        if (session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }
        if (!isset($_SESSION['user_id']))
        {
            header('Location: /login');
        }

        $userId = $_SESSION['user_id'];

        $result = $this->product->getCartInfo($userId);

        require_once './../View/cart.phtml';
    }

    public function addProduct()
    {
        if (session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }
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