<?php

namespace Controller;

use Core\ViewRenderer;
use Model\User;
use Request\LoginRequest;
use Request\RegistrateRequest;
use Service\Authentication\AuthenticationServiceInterface;

class UserController extends BaseController
{
    public function postLogin(LoginRequest $request): string
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

        return $this->renderer->render('get_login.phtml', ['errors' => $errors]);
    }

    public function getLogin(): string
    {
        if ($this->authenticationService->check()) {
            header("Location: /catalog");
        }

        return $this->renderer->render('get_login.phtml');
    }

    public function logout()
    {
        $this->authenticationService->logout();
        header('Location: /login');
    }

    public function postRegistrate(RegistrateRequest $request): string
    {
        $errors = $request->validate();

        if (empty($errors)) {
            $name = $request->getName();
            $email = $request->getEmail();
            $password = $request->getPassword();

            User::create($name, $email, $password);


            header('Location: /login');

        }

        return $this->renderer->render('get_registrate.phtml', ['errors' => $errors]);

    }

    public function getRegistrate(): string
    {
        if ($this->authenticationService->check()) {
            header("Location: /catalog");
        }

        return $this->renderer->render('get_registrate.phtml');

    }

}