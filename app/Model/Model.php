<?php
namespace Model;
use PDO;

class Model
{
    protected static ?PDO $pdo = null;

    public static function getPDO() : PDO
    {
        if (self::$pdo === null) {

            $dbHost = getenv('DB_HOST', 'db');
            $dbName = getenv('DB_NAME', 'dbtest');
            $dbUser = getenv('DB_USER', 'dbuser');
            $dbPassword = getenv('DB_PASSWORD', 'dbpwd');


            self::$pdo = new PDO("pgsql:host=$dbHost;port=5432;dbname=$dbName", "$dbUser", "$dbPassword");
        }

        return self::$pdo;
    }

    protected static function prepareExecute(string $sql, array $data)
    {
        $stmt = self::getPDO()->prepare($sql);

        foreach ($data as $param => $value)
        {
            $stmt->bindValue(":$param", $value);
            
        }
        $stmt->execute();

        return $stmt;

    }
}