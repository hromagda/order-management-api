<?php

namespace OrderManagementApi;

use OrderManagementApi\Controller\OrderController;
use OrderManagementApi\Repository\InMemoryOrderRepository;

class Router
{
    public function handleRequest(): void
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Dočasně jen na GET /orders
        if ($path === '/orders') {
            $repository = new InMemoryOrderRepository();
            $controller = new OrderController($repository);
            $controller->index();
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    }
}