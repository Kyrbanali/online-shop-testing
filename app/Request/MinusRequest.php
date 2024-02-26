<?php

namespace Request;

use Kurbanali\MyCore\Request\Request;

class MinusRequest extends Request
{
    public function getProductId(): ?int
    {
        return $this->body['product_id'];
    }

    public function validate(): array
    {
        $errors = [];

        $productId = $this->body['product_id'];

        if (empty($productId)) {
            $errors['product_id'] = 'Product ID is missing';
        } else {
            if (!ctype_digit($productId) || $productId <= 0) {
                $errors['product_id'] = 'Invalid Product ID';
            }
        }

        return $errors;
    }
}