<?php

namespace Controller\Api;

use Model\User;

class UserController
{
    public function index()
    {
        $users = User::all();

        $users = json_encode(['users' => $users], JSON_THROW_ON_ERROR);

        return $users;
    }

    public function create()
    {

    }

}