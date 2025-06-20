<?php

require_once __DIR__ . '/../vendor/autoload.php';

use OrderManagementApi\Router;

$router = new Router();
$router->handleRequest();