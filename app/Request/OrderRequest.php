<?php

namespace Request;

class OrderRequest extends Request
{
    public function getPhone()
    {
        return $this->body['user_phone'];
    }

    public function getAddress()
    {
        return $this->body['user_address'];
    }

    public function validate(): array
    {
        $errors = [];

        $phoneNumber = $this->body['user_phone'];
        $address = $this->body['user_address'];

        if (!isset($phoneNumber)) {
            $errors['user_phone'] = 'Поле phone не указано';
        } elseif (!preg_match('/^\d{7,15}$/', $phoneNumber)) {
                $errors['user_phone'] = 'Номер введен неверно';
        }

        if (!isset($address)) {
            $errors['user_address'] = 'Поле address не указано';
        } elseif (strlen($address) >= 5) {
            $errors['user_address'] = 'Введите больше 4х символов';
        }

        return $errors;

    }

}