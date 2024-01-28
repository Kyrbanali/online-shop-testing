<?php

namespace Controller;

use Model\User;
use Service\SessionService;
class UserController
{
    private SessionService $sessionService;
    private User $userModel;
    public function __construct()
    {
        $this->userModel = new User();
        $this->sessionService = new SessionService();
    }
    public function getRegistrate()
    {
        require_once './../View/get_registrate.phtml';
    }
    public function getLogin()
    {
        require_once './../View/get_login.phtml';
    }
    public function postLogin()
    {
        $errors = $this->validateLogin($_POST);

        if (empty($errors))
        {
            $email = $_POST['email'];
            $password = $_POST['psw'];

            $user = $this->userModel->getByEmail($email);

            if (!$user)
            {
                $errors['user'] = 'Пользователь с такими данными не зарегистрирован';
            }
            else
            {
                if (isset($user['password']) && $this->userModel->verifyPassword($password, $user['password']))
                {
                    $this->sessionService->setUser($user);
                }
                $errors['psw'] = 'wrong password';
            }


        }
        require_once('./../View/get_login.phtml');
    }
    public function postRegistrate()
    {
        $errors = $this->validate($_POST);

        if (empty($errors)) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['psw'];

            $this->userModel->registrate($name, $email, $password);


            header('Location: /login');

        }
        require_once './../View/get_registrate.phtml';
    }
    public function postLogout()
    {
        $this->sessionService->logout();
    }


    private function validateLogin(array $data) : array
    {
        $errors = [];

        if (isset($data['email']))
        {
            $email = $data['email'];
            if (strlen($email) < 2)
            {
                $errors['email'] = "Email length must be more than 1 character";

            }
            if (str_contains($email, '@'))
            {

            } else {
                $errors['email'] = "email doesn't contain '@' ";
            }
        } else {
            $errors['email'] = 'email is empty';
        }


        if (isset($data['psw']))
        {
            $password = $data['psw'];
            if (strlen($password) < 4)
            {
                $errors['psw'] = "Psw length must be more than 3 character";
            }
        } else {
            $errors['psw'] = 'psw is empty';
        }


        return $errors;
    }
    private function validate(array $data) : array
    {
        $errors = [];

        if (isset($data['name']))
        {
            $name = $data['name'];
            if (strlen($name) < 2)
            {
                $errors['name'] = "Name length must be more than 1 character";

            }
            if (preg_match("/\d/", $name))
            {
                $errors['name'] = "the name should not contain numbers";

            }
        } else {
            $errors['name'] = 'Name is empty';
        }

        if (isset($data['email']))
        {
            $email = $data['email'];
            if (strlen($email) < 2)
            {
                $errors['email'] = "Email length must be more than 1 character";

            }
            if (str_contains($email, '@'))
            {

            } else {
                $errors['email'] = "email doesn't contain '@' ";
            }
        } else {
            $errors['email'] = 'email is empty';
        }

        if (isset($data['psw']))
        {
            $password = $data['psw'];
            if (strlen($password) < 4)
            {
                $errors['psw'] = "Psw length must be more than 3 character";
            }
        } else {
            $errors['psw'] = 'psw is empty';
        }

        if (isset($data['psw-repeat']))
        {
            $passwordRepeat = $data['psw-repeat'];
            if (strlen($passwordRepeat) < 4)
            {
                $errors['psw-repeat'] = "Psw-repeat length must be more than 3 character";
            }
            if ($password !== $passwordRepeat)
            {
                $errors['psw-repeat'] = "Password mismatch";
            }
        } else {
            $errors['psw-repeat'] = "psw-repeat is empty";
        }

        return $errors;
    }

}