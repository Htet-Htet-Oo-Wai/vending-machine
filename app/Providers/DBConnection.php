<?php

namespace App\Providers;

use PDO;
use PDOException;

class DBConnection
{
    private static ?DBConnection $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        try {
            $dbConfig = [
                'host' => config('database.host', 'localhost'),
                'dbname' => config('database.dbname', 'vending_machine'),
            ];
            $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']}";
            $this->pdo = new PDO($dsn, config('database.username'), config('database.password'), config('database.options'));
        } catch (PDOException $e) {
            throw new PDOException("Database connection failed: " . $e->getMessage(), (int)$e->getCode());
        }
    }

    // Prevent cloning of the instance
    public function __clone() {}

    // Prevent unserializing the instance
    public function __wakeup() {}

    // Get the singleton instance
    public static function getInstance(): DBConnection
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Get the PDO instance
    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}
