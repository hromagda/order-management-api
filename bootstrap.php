<?php

use Dotenv\Dotenv;
use OrderManagementApi\Database\Connection;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['DATA_SOURCE'])->allowedValues(['db', 'memory']);
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

$config = [
    'data_source' => $_ENV['DATA_SOURCE'],
    'db' => [
        'host' => $_ENV['DB_HOST'],
        'name' => $_ENV['DB_NAME'],
        'user' => $_ENV['DB_USER'],
        'pass' => $_ENV['DB_PASS'],
    ],
];

// Vytvoření PDO instance, pokud je potřeba
$pdo = null;
if ($config['data_source'] === 'db') {
    $pdo = Connection::create($config['db']);
}

return [
    'config' => $config,
    'pdo' => $pdo,
];