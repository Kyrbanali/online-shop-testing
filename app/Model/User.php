<?php
namespace Model;
class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    public static function getOneByEmail(string $email)
    {
        $stmt = self::getPDO()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        $data = $stmt->fetch();

        $obj = new User();
        $obj->id = $data['id'];
        $obj->name = $data['name'];
        $obj->email = $data['email'];
        $obj->password = $data['password'];

        return $obj;
    }

    public static function registrate($name, $email, $password)
    {
        $hash = self::hashPassword($password);

        $stmt = self::getPDO()->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :hash)');
        $stmt->execute(['name' => $name, 'email' => $email, 'hash' => $hash]);
    }
    private static function hashPassword(string $password) : string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    public static function verifyPassword($password, $hashPassword) : bool
    {
        return password_verify($password, $hashPassword);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

}