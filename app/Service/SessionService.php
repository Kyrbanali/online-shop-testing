<?php

namespace Service;
class SessionService
{
    public static function startSession() : void
    {
        if (session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }
    }
    public static function requireLoggedInUser() : void
    {
        self::startSession();

        if (!isset($_SESSION['user_id']))
        {
            header('Location: /login');
        }
    }
    public static function logout() : void
    {
        self::startSession();

        $_SESSION['user_id'] = null;
        header('/login');
    }
    public static function setUser(int $user_id) : void
    {
        self::startSession();

        $_SESSION['user_id'] = $user_id;
        header('Location: /catalog');
    }
}