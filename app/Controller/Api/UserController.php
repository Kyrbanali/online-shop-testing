<?php

namespace Controller\Api;

use Controller\BaseController;
use Model\User;
use Request\Api\CreateUserRequest;

class UserController extends BaseController
{
    public function index()
    {
        $users = User::all();

        $users = json_encode(['users' => $users], JSON_THROW_ON_ERROR);

        return $users;
    }

    public function create(CreateUserRequest $request)
    {
        $errors = $request->validate();

        if (!empty($errors)) {
            return json_encode($errors);
        }

        $username = $request->getName();
        $email = $request->getEmail();
        $password = $request->getPassword();

        $user = User::create($username, $email, $password);

        return json_encode($user, JSON_THROW_ON_ERROR);

    }

}