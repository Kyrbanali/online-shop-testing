<?php
namespace Model;
use PDO;

class Model
{
    protected static PDO $pdo;
    protected static function getPDO() : PDO
    {
        self::$pdo = new PDO("pgsql:host=db;port=5432;dbname=dbtest", "dbuser", "dbpwd");
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