<?php

namespace OrderManagementApi\Repository;

use PDO;
use PDOException;

class DatabaseOrderRepository implements OrderRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->pdo->query('SELECT * FROM orders');
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(fn($row) => $this->mapRowToOrder($row), $orders);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

    public function findById(int $id): ?array
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ? $this->mapRowToOrder($row) : null;
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
            exit;
        }
    }

    private function mapRowToOrder(array $row): array
    {
        return [
            'id' => (int)$row['id'],
            'customer_name' => $row['customer_name'],
            'total_amount' => (float)$row['total_amount'],
            'status' => $row['status'],
            'created_at' => $row['created_at'],
            'updated_at' => $row['updated_at'],
        ];
    }
}