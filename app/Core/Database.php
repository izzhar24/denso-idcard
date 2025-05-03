<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $pdo;

    public static function getPDO()
    {
        if (!self::$pdo) {
            // Gunakan $_ENV atau getenv setelah .env diload
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $port = $_ENV['DB_PORT'] ?? '3306';
            $name = $_ENV['DB_DATABASE'] ?? 'your_database_name';
            $user = $_ENV['DB_USERNAME'] ?? 'root';
            $pass = $_ENV['DB_PASSWORD'] ?? '';

            try {
                self::$pdo = new PDO("mysql:host=$host;port=$port;dbname=$name", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                die("DB Connection failed: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
