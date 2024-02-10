<?php

namespace Request;

class MinusRequest extends Request
{
    public function getId()
    {
        return $this->body['product_id'];
    }
}