<?php

namespace Request;

use Kurbanali\MyCore\Request\Request;

class OrderRequest extends Request
{
    public function getPhone()
    {
        return $this->body['phone'];
    }

    public function getAddress()
    {
        return $this->body['address'];
    }

    public function validate(): array
    {
        $errors = [];

        $phoneNumber = $this->body['phone'];
        $address = $this->body['address'];

        if (!isset($phoneNumber)) {
            $errors['user_phone'] = 'Поле phone не указано';
        } elseif (!preg_match('/^\d{7,15}$/', $phoneNumber)) {
                $errors['user_phone'] = 'Номер введен неверно';
        }

        if (!isset($address)) {
            $errors['user_address'] = 'Поле address не указано';
        } elseif (!strlen($address) >= 5) {
            $errors['user_address'] = 'Введите больше 4х символов';
        }

        return $errors;

    }

}