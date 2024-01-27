<?php

class SessionService
{
    public function startSession() : void
    {
        if (session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }
    }

    public function requireLoggedInUser() : void
    {
        $this->startSession();

        if (!isset($_SESSION['user_id']))
        {
            header('Location: /login');
        }
    }

    public function logout() : void
    {
        $this->startSession();

        $_SESSION['user_id'] = null;
        header('/login');
    }

    public function setUser(array $user) : void
    {
        $this->startSession();

        $_SESSION['user_id'] = $user['id'];
        header('Location: /catalog');
    }
}