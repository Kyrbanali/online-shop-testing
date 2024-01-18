<?php
$flag = true;

if (isset($_POST['name']))
{
    $name = $_POST['name'];
    if (strlen($name) < 2)
    {
        echo "Name length must be more than 1 character\n";
        $flag = false;
    }
    if (preg_match("/\d/", $name))
    {
        echo "the name should not contain numbers\n";
        $flag = false;
    }
} else {
    echo 'Name is empty';
    $flag = false;
}

if (isset($_POST['email']))
{
    $email = $_POST['email'];
    if (str_contains($email, '@'))
    {

    } else {
        echo "email doesn't contain '@' ";
        $flag = false;
    }
} else {
    echo 'email is empty';
    $flag = false;
}

if (isset($_POST['psw']))
{
    $password = $_POST['psw'];
    if (strlen($password) < 4)
    {
        echo "Psw length must be more than 3 character\n";
        $flag = false;
    }
} else {
    echo 'psw is empty';
    $flag = false;
}

if (isset($_POST['psw-repeat']))
{
    $passwordRepeat = $_POST['psw-repeat'];
    if (strlen($passwordRepeat) < 4)
    {
        echo "Psw-repeat length must be more than 3 character\n";
        $flag = false;
    }
    if ($password !== $passwordRepeat)
    {
        echo "Password mismatch\n";
        $flag = false;
    }
} else {
    echo "psw-repeat is empty";
    $flag = false;
}




$pdo = new PDO("pgsql:host=db;port=5432;dbname=dbtest", "dbuser", "dbpwd");
if ($flag) {
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');
    $stmt->execute(['name' => $name, 'email' => $email, 'password' => $password]);
}
