<?php

namespace OrderManagementApi;

use OrderManagementApi\Controller\OrderController;
use OrderManagementApi\Repository\InMemoryOrderRepository;
use OrderManagementApi\Repository\DatabaseOrderRepository;
use OrderManagementApi\Repository\OrderRepositoryInterface;
use PDO;
use PDOException;

class Router
{
    private OrderRepositoryInterface $repository;

    public function __construct(OrderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function handleRequest(): void
    {
        try {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $method = $_SERVER['REQUEST_METHOD'];

            $controller = new OrderController($this->repository);

            if ($method === 'GET' && preg_match('#^/orders/?$#', $path)) {
                $controller->index();
            } elseif ($method === 'GET' && preg_match('#^/orders/(\d+)$#', $path, $matches)) {
                $orderId = (int) $matches[1];
                $controller->show($orderId);
            } else {
                $this->sendNotFound();
            }

        } catch (\Exception $e) {
            $this->sendError($e);
        }
    }

    private function sendNotFound(): void
    {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not Found']);
    }

    private function sendError(\Exception $e): void
    {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode([
            'error' => 'Internal server error',
            'details' => $e->getMessage()
        ]);
    }
}