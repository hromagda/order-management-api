<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

use OrderManagementApi\Router;

$router = new Router();
$router->handleRequest();