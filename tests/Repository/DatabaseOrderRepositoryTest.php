<?php

namespace OrderManagementApi\Tests\Repository;

use PHPUnit\Framework\TestCase;
use OrderManagementApi\Database\Connection;
use OrderManagementApi\Database\DatabaseConfig;
use OrderManagementApi\Repository\DatabaseOrderRepository;
use OrderManagementApi\Model\Order;
use PDO;

class DatabaseOrderRepositoryTest extends TestCase
{
    private DatabaseOrderRepository $repository;
    private PDO $pdo;

    protected function setUp(): void
    {
        // Načti .env pokud ještě nebyl načtený
        if (!isset($_ENV['DB_USER'])) {
            $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/../../'); // uprav cestu k .env podle struktury
            $dotenv->load();
        }

        // Načte konfiguraci z ENV
        $config = DatabaseConfig::load();
        $this->pdo = Connection::create($config);
        $this->pdo->beginTransaction();

        $this->repository = new DatabaseOrderRepository($this->pdo);
    }

    protected function tearDown(): void
    {
        // Rollback změn v DB, test bude idempotentní
        $this->pdo->rollBack();
    }

    public function testFindAllReturnsArrayOfOrders()
    {
        $orders = $this->repository->findAll();

        $this->assertIsArray($orders);
        $this->assertNotEmpty($orders);
        $this->assertInstanceOf(Order::class, $orders[0]);
    }

    public function testFindByIdReturnsOrderWhenExists()
    {
        $order = $this->repository->findById(1);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(1, $order->getId());
    }

    public function testFindByIdReturnsNullWhenNotExists()
    {
        $order = $this->repository->findById(999999);

        $this->assertNull($order);
    }

    public function testFindByIdWithItemsReturnsOrderWithItems()
    {
        $order = $this->repository->findByIdWithItems(1);

        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals(1, $order->getId());

        $items = $order->getItems();
        $this->assertIsArray($items);
        $this->assertNotEmpty($items);

        // Každý item je instancí OrderItem
        $this->assertInstanceOf(\OrderManagementApi\Model\OrderItem::class, $items[0]);
    }

    public function testFindByIdWithItemsReturnsNullWhenNotExists()
    {
        $order = $this->repository->findByIdWithItems(999999);

        $this->assertNull($order);
    }
}