<?php

namespace Request;

use Kurbanali\MyCore\Request\Request;

class LoginRequest extends Request
{
    public function getName(): string
    {
        return $this->body['name'];
    }

    public function getEmail(): string
    {
        return $this->body['email'];
    }

    public function getPassword(): string
    {
        return $this->body['psw'];
    }

    public function validate(): array
    {
        $errors = [];

        if (!isset($this->body['email'])) {
            $errors['email'] = 'Поле email не указано';
        }
        if (!isset($this->body['psw'])) {
            $errors['psw'] = 'Поле password не указано';
        }

        return $errors;
    }
}