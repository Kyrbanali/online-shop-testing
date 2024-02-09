<?php
namespace Model;
class User extends Model
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'] ;
        $this->email = $data['email'] ;
        $this->password = $data['password'];
    }
    public static function getOneByEmail(string $email): ?User
    {
        $stmt = self::getPDO()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        $data = $stmt->fetch();
        if (empty($data)) {
            return null;
        }

        return new User($data);
    }
    public static function getOneById(string $id): ?User
    {
        $stmt = self::getPDO()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch();
        if (empty($data)) {
            return null;
        }

        return new User($data);
    }

    public static function create($name, $email, $password): void
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = self::getPDO()->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :hash)');
        $stmt->execute(['name' => $name, 'email' => $email, 'hash' => $hash]);
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