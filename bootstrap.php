<?php

use Dotenv\Dotenv;
use OrderManagementApi\Database\Connection;
use OrderManagementApi\Database\DatabaseConfig;

// Načti .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Validace .env hodnot
$dotenv->required(['DATA_SOURCE'])->allowedValues(['db', 'memory']);
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

// Konfigurace
$config = [
    'data_source' => $_ENV['DATA_SOURCE'],
];

// Připojení k DB, pokud je potřeba
$pdo = null;
if ($config['data_source'] === 'db') {
    $dbConfig = DatabaseConfig::load();
    $pdo = Connection::create($dbConfig);
}

return [
    'config' => $config,
    'pdo' => $pdo,
];