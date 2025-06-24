<?php

declare(strict_types=1);

namespace OrderManagementApi\Tests\Repository;

use PHPUnit\Framework\TestCase;
use OrderManagementApi\Repository\InMemoryOrderRepository;

final class OrderRepositoryInMemoryTest extends TestCase
{
    public function testFindAllReturnsOrders(): void
    {
        $repo = new InMemoryOrderRepository();
        $orders = $repo->findAll();

        $this->assertIsArray($orders);
        $this->assertNotEmpty($orders);
    }

    public function testFindByIdReturnsOrder(): void
    {
        $repo = new InMemoryOrderRepository();
        $order = $repo->findById(1);

        $this->assertNotNull($order);
        $this->assertSame(1, $order->getId());
    }

    public function testFindByIdReturnsNullForNonExisting(): void
    {
        $repo = new InMemoryOrderRepository();
        $order = $repo->findById(999);

        $this->assertNull($order);
    }

    public function testFindByIdWithItemsReturnsOrderWithItems(): void
    {
        $repo = new InMemoryOrderRepository();
        $order = $repo->findByIdWithItems(1);

        $this->assertNotNull($order);
        $this->assertSame(1, $order->getId());
        $this->assertIsArray($order->getItems());
        $this->assertNotEmpty($order->getItems(), 'Order should have items');
    }

    public function testFindByIdWithItemsReturnsNullForNonExisting(): void
    {
        $repo = new InMemoryOrderRepository();
        $order = $repo->findByIdWithItems(999);

        $this->assertNull($order);
    }

    public function testFindByIdWithItemsReturnsOrderWithoutItems(): void
    {
        $repo = new InMemoryOrderRepository();
        $order = $repo->findByIdWithItems(3);

        $this->assertNotNull($order, 'Objednávka by měla existovat');
        $this->assertIsArray($order->getItems(), 'Položky by měly být pole');
        $this->assertEmpty($order->getItems(), 'Položky by měly být prázdné');
    }

}