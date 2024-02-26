<?php
namespace Model;

use Kurbanali\MyCore\Model\Model;

class User extends Model implements \JsonSerializable
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;

    public function __construct(int $id, string $name, string $email, string $password)
    {
        $this->id = $id;
        $this->name = $name ;
        $this->email = $email;
        $this->password = $password;
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

    public static function all(): ?array
    {
        $sql = <<<SQL
            select *
            from users
        SQL;

        $stmt = self::getPDO()->query($sql);
        $users = $stmt->fetchAll();

        foreach ($users as $user) {
            $data[] = new User(
                $user['id'],
                $user['name'],
                $user['email'],
                $user['password']
            );
        }

        if (empty($data)) {
            return null;
        }

        return $data;

    }

    public static function getOneByEmail(string $email): ?User
    {
        $stmt = self::getPDO()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);

        $data = $stmt->fetch();
        if (empty($data)) {
            return null;
        }

        return self::hydrate($data);
    }

    public static function getOneById(int $id): ?User
    {
        $stmt = self::getPDO()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);

        $data = $stmt->fetch();
        if (empty($data)) {
            return null;
        }

        return self::hydrate($data);
    }

    public static function create($name, $email, $password): ?User
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = <<<SQL
        INSERT INTO users (name, email, password)
        VALUES (:name, :email, :hash)
        RETURNING id
        SQL;

        $data = [
            'name' => $name,
            'email' => $email,
            'hash' => $hash
        ];

        $stmt = self::prepareExecute($sql, $data);
        $result = $stmt->fetch();

        if (!$result) {
            return null;
        }

        $userId = $result['id'];

        return self::getOneById($userId);
    }

    private static function hydrate(array $data): User
    {
        return new User(
            $data['id'],
            $data['name'],
            $data['email'],
            $data['password']
        );
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}