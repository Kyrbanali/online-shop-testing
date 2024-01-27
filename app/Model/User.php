<?php

//require_once 'Model.php';
class User extends Model
{
    public function getByEmail(string $email)
    {
        $stmt = $this->pdo->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        if ($stmt)
        {
            return $stmt->fetch();
        } else {
            return false;
        }
    }

    public function registrate($name, $email, $password)
    {
        $hash = $this->hashPassword($password);

        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :hash)');
        $stmt->execute(['name' => $name, 'email' => $email, 'hash' => $hash]);
    }

    private function hashPassword(string $password) : string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    public function verifyPassword($password, $hashPassword) : bool
    {
        return password_verify($password, $hashPassword);
    }


}