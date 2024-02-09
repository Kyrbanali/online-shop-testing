<?php

namespace Controller;

use Model\User;
use Request\Request;
use Service\SessionAuthenticationService;
class UserController
{
    private SessionAuthenticationService $authenticationService;
    public function __construct()
    {
        $this->authenticationService = new SessionAuthenticationService();
    }
    public function getLogin()
    {
        require_once './../View/get_login.phtml';
    }
    public function postLogin(Request $request)
    {
        $errors = $request->getErrors();
        if (empty($errors))
        {
            $email = $request->getOneByKey('email');
            $password = $request->getOneByKey('psw');

            $result = $this->authenticationService->login($email, $password);

            if ($result) {
                header("Location: /catalog");
            } else {
                $errors['user'] = "Пользователь с такими данными не зарегистрирован";
            }
        }
        require_once('./../View/get_login.phtml');
    }
    public function getRegistrate()
    {
        require_once './../View/get_registrate.phtml';
    }
    public function postRegistrate(Request $request)
    {
        $errors = $request->getErrors();

        if (empty($errors)) {
            $name = $request->getOneByKey('name');
            $email = $request->getOneByKey('email');
            $password = $request->getOneByKey('psw');

            User::create($name, $email, $password);


            header('Location: /login');

        }
        require_once './../View/get_registrate.phtml';
    }
    public function logout()
    {
        $this->authenticationService->logout();
        header('Location: /login');
    }

}