<?php


$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['psw'];
$passwordRepeat = $_POST['psw-repeat'];

$pdo = new PDO("pgsql:host=db;port=5432;dbname=dbtest", "dbuser", "dbpwd");


$pdo->exec("INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");