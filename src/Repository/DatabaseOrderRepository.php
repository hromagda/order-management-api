<?php

namespace OrderManagementApi\Repository;

use OrderManagementApi\Exception\DatabaseException;
use OrderManagementApi\Model\Order;
use OrderManagementApi\Model\OrderItem;
use PDO;
use PDOException;

class DatabaseOrderRepository implements OrderRepositoryInterface
{
    /**
     * PDO instance pro přístup do DB
     * @var PDO
     */
    private PDO $pdo;

    /**
     * Konstruktor
     *
     * @param PDO $pdo PDO instance připojení k databázi
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Načte všechny objednávky z databáze
     *
     * @return Order[] Pole objednávek
     * @throws DatabaseException V případě chyby v DB dotazu
     */
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

    /**
     * Najde objednávku podle ID
     *
     * @param int $id ID objednávky
     * @return Order|null Vrací objednávku nebo null, pokud neexistuje
     * @throws DatabaseException V případě chyby v DB dotazu
     */
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

    /**
     * Najde objednávku včetně položek podle ID
     *
     * @param int $id ID objednávky
     * @return Order|null Vrací objednávku s položkami nebo null, pokud neexistuje
     * @throws DatabaseException V případě chyby v DB dotazu
     */
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

            // Vytvoří nový objekt Order s přidanými položkami
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

    /**
     * Mapuje pole z DB na entitu Order
     *
     * @param array $row Data z DB
     * @return Order
     */
    private function mapRowToOrder(array $row): Order
    {
        return new Order(
            (int)$row['id'],
            $row['created_at'],
            $row['customer_name'],
            (float)$row['total_amount'],
            'CZK',          // pevná měna
            $row['status']
        );
    }
}