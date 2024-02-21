<?php

namespace Controller;

use Model\User;
use Request\LoginRequest;
use Request\RegistrateRequest;
use Service\Authentication\AuthenticationServiceInterface;

class UserController
{
    private AuthenticationServiceInterface $authenticationService;

    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function postLogin(LoginRequest $request): array
    {
        $errors = $request->validate();
        if (empty($errors))
        {
            $email = $request->getEmail();
            $password = $request->getPassword();

            $result = $this->authenticationService->login($email, $password);

            if ($result) {
                header("Location: /catalog");
            } else {
                $errors['user'] = "Пользователь с такими данными не зарегистрирован";
            }
        }
        return [
            'view' => 'get_login.phtml',
            'params' => [
                'errors' => $errors,
            ],
        ];
    }

    public function getLogin(): array
    {
        return [
            'view' => 'get_login.phtml',
        ];
    }

    public function logout()
    {
        $this->authenticationService->logout();
        header('Location: /login');
    }

    public function postRegistrate(RegistrateRequest $request): array
    {
        $errors = $request->validate();

        if (empty($errors)) {
            $name = $request->getName();
            $email = $request->getEmail();
            $password = $request->getPassword();

            User::create($name, $email, $password);


            header('Location: /login');

        }

        return [
            'view' => 'get_registrate.phtml',
            'params' => [
                'errors' => $errors,
            ],
        ];    }

    public function getRegistrate(): array
    {
        return [
            'view' => 'get_registrate.phtml',
        ];    }

}