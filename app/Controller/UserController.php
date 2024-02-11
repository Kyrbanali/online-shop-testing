<?php

namespace Controller;

use Model\User;
use Request\LoginRequest;
use Request\RegistrateRequest;
use Service\SessionAuthenticationService;
class UserController
{
    private SessionAuthenticationService $authenticationService;

    public function __construct()
    {
        $this->authenticationService = new SessionAuthenticationService();
    }

    public function postLogin(LoginRequest $request)
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
        require_once('./../View/get_login.phtml');
    }

    public function getLogin()
    {
        require_once './../View/get_login.phtml';
    }

    public function logout()
    {
        $this->authenticationService->logout();
        header('Location: /login');
    }

    public function postRegistrate(RegistrateRequest $request)
    {
        $errors = $request->validate();

        if (empty($errors)) {
            $name = $request->getName();
            $email = $request->getEmail();
            $password = $request->getPassword();

            User::create($name, $email, $password);


            header('Location: /login');

        }
        require_once './../View/get_registrate.phtml';
    }

    public function getRegistrate()
    {
        require_once './../View/get_registrate.phtml';
    }

}