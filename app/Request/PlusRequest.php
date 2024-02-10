<?php

namespace Request;

class PlusRequest extends Request
{
    public function getId()
    {
        return $this->body['product_id'];
    }

}