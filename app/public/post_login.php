<?php

$errors = [];

if (isset($_POST['email']))
{
    $email = $_POST['email'];
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

if (isset($_POST['psw']))
{
    $password = $_POST['psw'];
    if (strlen($password) < 4)
    {
        $errors['psw'] = "Psw length must be more than 3 character";
    }
} else {
    $errors['psw'] = 'psw is empty';
}


if (empty($errors))
{
    $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbtest", "dbuser", "dbpwd");

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $data = $stmt->fetch();



    if ($password === $data['password']) print_r($data);
    else {
        $errors['psw'] = 'wrong password';
        require_once('./get_login.php');
    }

} else require_once('./get_login.php');
?>