<?php

namespace OrderManagementApi\Repository;

use PDO;
use PDOException;
use OrderManagementApi\Model\Order;
use OrderManagementApi\Model\OrderItem;
use OrderManagementApi\Exception\DatabaseException;

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
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return array_map(fn($row) => $this->mapRowToOrder($row), $rows);
        } catch (PDOException $e) {
            throw new DatabaseException('Database error: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findById(int $id): ?Order
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM orders WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row ? $this->mapRowToOrder($row) : null;
        } catch (PDOException $e) {
            throw new DatabaseException('Database error: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findByIdWithItems(int $id): ?Order
    {
        try {
            $stmt = $this->pdo->prepare(
                'SELECT o.*, i.id AS item_id, i.product_name, i.quantity, i.price
                 FROM orders o
                 LEFT JOIN items i ON i.order_id = o.id
                 WHERE o.id = :id'
            );
            $stmt->execute(['id' => $id]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$rows) {
                return null;
            }

            $order = $this->mapRowToOrder($rows[0]);
            $items = [];

            foreach ($rows as $row) {
                if ($row['item_id']) {
                    $items[] = new OrderItem(
                        $row['product_name'],
                        (float)$row['price'],
                        (int)$row['quantity']
                    );
                }
            }

            return new Order(
                $order->toArray()['id'],
                $order->toArray()['date'],
                $order->toArray()['description'],
                $order->toArray()['total'],
                $order->toArray()['currency'],
                $order->toArray()['status'],
                $items
            );
        } catch (PDOException $e) {
            throw new DatabaseException('Database error: ' . $e->getMessage(), 0, $e);
        }
    }

    private function mapRowToOrder(array $row): Order
    {
        return new Order(
            (int)$row['id'],
            $row['created_at'],
            $row['customer_name'],
            (float)$row['total_amount'],
            'CZK',
            $row['status']
        );
    }
}