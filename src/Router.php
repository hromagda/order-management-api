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
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    private function createRepository(): OrderRepositoryInterface
    {
        if ($this->config['data_source'] === 'db') {
            $pdo = new PDO(
                'mysql:host=' . $this->config['db']['host'] . ';dbname=' . $this->config['db']['name'] . ';charset=utf8',
                $this->config['db']['user'],
                $this->config['db']['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
            return new DatabaseOrderRepository($pdo);
        }

        return new InMemoryOrderRepository();
    }

    public function handleRequest(): void
    {
        try {
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $method = $_SERVER['REQUEST_METHOD'];

            $repository = $this->createRepository();
            $controller = new OrderController($repository);

            if ($method === 'GET' && preg_match('#^/orders/?$#', $path)) {
                $controller->index();
            } elseif ($method === 'GET' && preg_match('#^/orders/(\d+)$#', $path, $matches)) {
                $orderId = (int) $matches[1];
                $controller->show($orderId);
            } else {
                $this->sendNotFound();
            }

        } catch (PDOException $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Database connection failed',
                'details' => $e->getMessage()
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'Internal server error',
                'details' => $e->getMessage()
            ]);
        }
    }

    private function sendNotFound(): void
    {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not Found']);
    }
}