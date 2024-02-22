<?php

namespace Controller\Api;

use Controller\BaseController;
use Model\User;
use Request\CreateUserRequest;

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

        if (empty($errors)){
            $username = $request->getName();
            $email = $request->getEmail();
            $password = $request->getPassword();
        }

//        $data = json_decode(file_get_contents('php://input'), true);
//        // Проверка и валидация данных
//        $username = isset($data['username']) ? trim($data['username']) : null;
//        $email = isset($data['email']) ? trim($data['email']) : null;
//        $password = isset($data['password']) ? trim($data['password']) : null;

    }

    public function test()
    {
        return $this->renderer->render('create_user.html');

    }

}