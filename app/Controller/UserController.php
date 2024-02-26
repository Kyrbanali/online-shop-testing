<?php

namespace Controller;

use Model\User;
use Request\LoginRequest;
use Request\RegistrateRequest;

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

    public function logout(): void
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

            $user = User::create($name, $email, $password);


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