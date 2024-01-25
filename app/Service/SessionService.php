<?php


class SessionService
{
    public function startSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }

    }

    public function requireLoggedInUser()
    {
        $this->startSession();

        if (!isset($_SESSION['user_id']))
        {
            header('Location: /login');
        }
    }

    public function logout()
    {
        $this->startSession();

        $_SESSION['user_id'] = null;
        header('/login');
    }

    public function setUser($user)
    {
        $this->startSession();

        $_SESSION['user_id'] = $user['id'];
        header('Location: /catalog');
    }
}