<?php


class ProductController
{
    private Product $product;
    private UserProduct $userProduct;
    private SessionService $sessionService;

    public function __construct()
    {
        $this->product = new Product();
        $this->userProduct = new UserProduct();
        $this->sessionService = new SessionService();
    }
    public function getCatalog()
    {
        $this->sessionService->requireLoggedInUser();
        $userId = $_SESSION['user_id'];

        $products = $this->product->getAll();
        $result = $this->product->getCartInfo($userId);

        require_once './../View/catalog.phtml';
    }

    public function getCart()
    {

        $this->sessionService->requireLoggedInUser();

        $userId = $_SESSION['user_id'];

        $products = $this->product->getAllToCart($userId);
        $result = $this->product->getCartInfo($userId);

        require_once './../View/cart.phtml';
    }

    public function processCart() : void
    {
        $this->sessionService->requireLoggedInUser();

        $errors = $this->validate($_POST);

        if (empty($errors))
        {
            $userId = $_SESSION['user_id'];
            $productId = $_POST['product_id'];
            $action = $_POST['action'];

            switch ($action)
            {
                case 'inc':
                    $this->userProduct->updateOrCreate($userId, $productId);
                    break;

                case 'dec':
                    $this->userProduct->updateOrDelete($userId, $productId);
                    break;
            }
        }

    }



    private function validate(array $data): array
    {
        $errors = [];

        if (!isset($data['product_id']))
        {
            $errors['product'] = 'product is empty';
        }

        return $errors;
    }

}