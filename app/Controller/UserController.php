<?php

namespace Controller;

use Core\ViewRenderer;
use Model\User;
use Request\LoginRequest;
use Request\RegistrateRequest;
use Service\Authentication\AuthenticationServiceInterface;

class UserController
{
    private AuthenticationServiceInterface $authenticationService;
    private ViewRenderer $renderer;

    public function __construct(AuthenticationServiceInterface $authenticationService, ViewRenderer $renderer)
    {
        $this->authenticationService = $authenticationService;
        $this->renderer = $renderer;
    }

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
        return $this->renderer->render('get_registrate.phtml');

    }

}