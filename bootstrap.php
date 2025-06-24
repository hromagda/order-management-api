<?php

use Dotenv\Dotenv;
use OrderManagementApi\Database\Connection;
use OrderManagementApi\Database\DatabaseConfig;

/**
 * Bootstrap aplikace - načte konfiguraci z .env,
 * připraví PDO připojení k databázi podle nastavení.
 *
 * @return array{
 *     config: array{data_source: string},
 *     pdo: ?\PDO
 * }
 */

// Načte .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Validace povinných .env hodnot
$dotenv->required(['DATA_SOURCE'])->allowedValues(['db', 'memory']);
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);

// Konfigurace zdroje dat
$config = [
    'data_source' => $_ENV['DATA_SOURCE'],
];

// Inicializace PDO, pokud je zdroj dat databáze
$pdo = null;
if ($config['data_source'] === 'db') {
    $dbConfig = DatabaseConfig::load();
    $pdo = Connection::create($dbConfig);
}

return [
    'config' => $config,
    'pdo' => $pdo,
];