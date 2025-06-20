<?php

namespace OrderManagementApi;

use OrderManagementApi\Controller\OrderController;
use OrderManagementApi\Repository\InMemoryOrderRepository;
use OrderManagementApi\Repository\DatabaseOrderRepository;
use PDO;
use PDOException;

class Router
{
    public function handleRequest(): void
    {
        try {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $method = $_SERVER['REQUEST_METHOD'];

            if ($_ENV['DATA_SOURCE'] === 'db') {
                $pdo = new PDO(
                    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8',
                    $_ENV['DB_USER'],
                    $_ENV['DB_PASS'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
                $repository = new DatabaseOrderRepository($pdo);
            } else {
                $repository = new InMemoryOrderRepository();
            }

            $controller = new OrderController($repository);

            if ($method === 'GET' && preg_match('#^/orders/?$#', $path)) {
                $controller->index();
            } elseif ($method === 'GET' && preg_match('#^/orders/(\d+)$#', $path, $matches)) {
                $orderId = (int)$matches[1];
                $controller->show($orderId);
            } else {
                $this->sendNotFound();
            }

        } catch (PDOException $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Database connection failed', 'details' => $e->getMessage()]);
        } catch (\Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Internal server error', 'details' => $e->getMessage()]);
        }
    }

    private function sendNotFound(): void
    {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not Found']);
    }
}