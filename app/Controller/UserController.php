<?php

namespace Controller;

use Model\User;
use Request\Request;
use Service\SessionService;
class UserController
{
    public function getRegistrate()
    {
        require_once './../View/get_registrate.phtml';
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

            $user = User::getOneByEmail($email);

            if (!$user)
            {
                $errors['user'] = 'Пользователь с такими данными не зарегистрирован';
            }
            else
            {
                if (User::verifyPassword($password, $user->getPassword()))
                {
                    SessionService::setUser($user->getId());
                }
                $errors['psw'] = 'wrong password';
            }
        }
        require_once('./../View/get_login.phtml');
    }
    public function postRegistrate(Request $request)
    {
        $errors = $request->getErrors();

        if (empty($errors)) {
            $name = $request->getOneByKey('name');
            $email = $request->getOneByKey('email');
            $password = $request->getOneByKey('psw');

            User::registrate($name, $email, $password);


            header('Location: /login');

        }
        require_once './../View/get_registrate.phtml';
    }
    public function logout()
    {
        SessionService::logout();
        header('Location: /login');
    }

}