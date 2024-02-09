<?php

namespace Service;

use Model\User;

class CookieAuthenticationService implements AuthenticationInterface
{
    public function login(string $email, string $password) : bool
    {
        $user = User::getOneByEmail($email);

        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user->getPassword())) {
            return false;
        }

        setcookie('user_id', $user->getId());

        return true;

    }
    public function getCurrentUser(): ?User
    {
        if (!$this->check()) {
            return null;
        }

        $userId = $_COOKIE['user_id'];

        return User::getOneById($userId);

    }
    public function logout(): void
    {
        if ($this->check()) {
            $_SESSION['user_id'] = null;
        }

    }
    public function check(): bool
    {
        return isset($_COOKIE['user_id']);
    }

}