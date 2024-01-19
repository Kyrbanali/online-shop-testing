<?php

$errors = [];

if (isset($_POST['name']))
{
    $name = $_POST['name'];
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

if (isset($_POST['psw-repeat']))
{
    $passwordRepeat = $_POST['psw-repeat'];
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




if (empty($errors)) {
    $pdo = new PDO("pgsql:host=db;port=5432;dbname=dbtest", "dbuser", "dbpwd");

    $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
    $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $data = $stmt->fetch();

    print_r($data);

} else {
    require_once('./get_registrate.php');
}


?>