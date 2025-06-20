<?php

namespace OrderManagementApi;

use OrderManagementApi\Controller\OrderController;
use OrderManagementApi\Repository\InMemoryOrderRepository;

class Router
{
    public function handleRequest(): void
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET' && preg_match('#^/orders/?$#', $path)) {
            $repository = new InMemoryOrderRepository();
            $controller = new OrderController($repository);
            $controller->index();
        } elseif ($method === 'GET' && preg_match('#^/orders/(\d+)$#', $path, $matches)) {
            $orderId = (int) $matches[1];
            $repository = new InMemoryOrderRepository();
            $controller = new OrderController($repository);
            $controller->show($orderId);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    }
}