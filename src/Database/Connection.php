<?php

namespace OrderManagementApi\Database;

use PDO;
use PDOException;

class Connection
{
    public static function create(array $config): PDO
    {
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $config['host'], $config['name']);

        try {
            $pdo = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);

            return $pdo;
        } catch (PDOException $e) {
            // Případně zde můžeš vyhodit DatabaseException nebo logovat
            die('Database connection failed: ' . $e->getMessage());
        }
    }
}