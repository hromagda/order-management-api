<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../bootstrap.php';

use OrderManagementApi\Router;

$router = new Router($config);
$router->handleRequest();