<?php

class UserController
{
    public function getRegistrate()
    {
        require_once './../View/get_registrate.phtml';
    }

    public function getLogin()
    {
        require_once './../View/get_login.phtml';
    }

    public function postLogin()
    {
        $errors = $this->validateLogin($_POST);

        if (empty($errors))
        {
            $email = $_POST['email'];
            $password = $_POST['psw'];

            $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbtest", "dbuser", "dbpwd");

            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);

            if (!$stmt->rowCount())
            {
                $errors['user'] = 'Пользователь с такими данными не зарегистрирован';
            }

            $user = $stmt->fetch();


            if (password_verify($password, $user['password']))
            {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                header('Location: /catalog');
            }
            $errors['psw'] = 'wrong password';
        }
        require_once('./../View/get_login.phtml');
    }
    public function postRegistrate()
    {
        $errors = $this->validate($_POST);

        if (empty($errors)) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['psw'];
            $passwordRepeat = $_POST['psw-repeat'];
            $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbtest", "dbuser", "dbpwd");

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :hash)');
            $stmt->execute(['name' => $name, 'email' => $email, 'hash' => $hash]);


            header('Location: /login');

        }
        require_once './../View/get_registrate.phtml';
    }

    public function postLogout()
    {
        session_start();
        $_SESSION['user_id'] = null;
        header('/login');
    }


    private function validateLogin(array $data) : array
    {
        $errors = [];

        if (isset($data['email']))
        {
            $email = $data['email'];
            if (strlen($email) < 2)
            {
                $errors['email'] = "Email length must be more than 1 character";

            }
            if (str_contains($email, '@'))
            {

            } else {
                $errors['email'] = "email doesn't contain '@' ";
            }
        } else {
            $errors['email'] = 'email is empty';
        }


        if (isset($data['psw']))
        {
            $password = $data['psw'];
            if (strlen($password) < 4)
            {
                $errors['psw'] = "Psw length must be more than 3 character";
            }
        } else {
            $errors['psw'] = 'psw is empty';
        }


        return $errors;
    }
    private function validate(array $data) : array
    {
        $errors = [];

        if (isset($data['name']))
        {
            $name = $data['name'];
            if (strlen($name) < 2)
            {
                $errors['name'] = "Name length must be more than 1 character";

            }
            if (preg_match("/\d/", $name))
            {
                $errors['name'] = "the name should not contain numbers";

            }
        } else {
            $errors['name'] = 'Name is empty';
        }

        if (isset($data['email']))
        {
            $email = $data['email'];
            if (strlen($email) < 2)
            {
                $errors['email'] = "Email length must be more than 1 character";

            }
            if (str_contains($email, '@'))
            {

            } else {
                $errors['email'] = "email doesn't contain '@' ";
            }
        } else {
            $errors['email'] = 'email is empty';
        }

        if (isset($data['psw']))
        {
            $password = $data['psw'];
            if (strlen($password) < 4)
            {
                $errors['psw'] = "Psw length must be more than 3 character";
            }
        } else {
            $errors['psw'] = 'psw is empty';
        }

        if (isset($data['psw-repeat']))
        {
            $passwordRepeat = $data['psw-repeat'];
            if (strlen($passwordRepeat) < 4)
            {
                $errors['psw-repeat'] = "Psw-repeat length must be more than 3 character";
            }
            if ($password !== $passwordRepeat)
            {
                $errors['psw-repeat'] = "Password mismatch";
            }
        } else {
            $errors['psw-repeat'] = "psw-repeat is empty";
        }

        return $errors;
    }

}