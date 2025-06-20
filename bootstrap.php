<?php

use Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['DATA_SOURCE'])->allowedValues(['db', 'memory']);
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);