<?php
// src/Helper/Database.php

namespace App\Helper;

use PDO;
use PDOException;
use Exception;

class Database
{
    public const SELECTSINGLE = 1;
    public const SELECTALL    = 2;
    public const EXECUTE      = 3;

    private PDO $pdo;

    public function __construct(
        string $dsn     = 'mysql:host=localhost;dbname=TodoApp;charset=utf8mb4',
        string $user    = 'mostafa',
        string $pass    = 'abcd@1234'
    ) {
        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    /**
     * @param string $sql
     * @param int    $mode   One of self::SELECTSINGLE, SELECTALL, EXECUTE
     * @param array  $params Indexed array of [$placeholder, $value]
     * @return mixed
     * @throws Exception on invalid mode or execution error
     */
    public function queryDB(string $sql, int $mode, array $params = [])
    {
        $stmt = $this->pdo->prepare($sql);

        foreach ($params as [$placeholder, $value]) {
            $stmt->bindValue($placeholder, $value);
        }

        try {
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("SQL Error [{$e->getCode()}]: {$e->getMessage()}");
            throw new Exception('Database query failed.');
        }

        return match ($mode) {
            self::SELECTSINGLE => $stmt->fetch(),
            self::SELECTALL    => $stmt->fetchAll(),
            self::EXECUTE      => true,
            default            => throw new Exception('Invalid query mode'),
        };
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
