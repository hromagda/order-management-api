<?php

/**
 * Hlavní vstupní bod aplikace.
 *
 * Načítá bootstrap konfiguraci a na základě ní vytváří odpovídající repozitář objednávek.
 * Inicializuje router a předává mu repozitář pro zpracování HTTP požadavků.
 */

require_once __DIR__ . '/../vendor/autoload.php';

$bootstrap = require __DIR__ . '/../bootstrap.php';

use OrderManagementApi\Router;
use OrderManagementApi\Repository\DatabaseOrderRepository;
use OrderManagementApi\Repository\InMemoryOrderRepository;
use OrderManagementApi\Repository\ApiOrderRepository;

// Získá konfiguraci a PDO
$config = $bootstrap['config'];
$pdo = $bootstrap['pdo'];

// Rozhodne, jaký repozitář použít
if ($config['data_source'] === 'db') {
    $repository = new DatabaseOrderRepository($pdo);
} elseif ($config['data_source'] === 'api') {
    $repository = new ApiOrderRepository($_ENV['API_URL']);
} else {
    $repository = new InMemoryOrderRepository();
}

// Inicializace routeru s repozitářem
$router = new Router($repository);
$router->handleRequest();