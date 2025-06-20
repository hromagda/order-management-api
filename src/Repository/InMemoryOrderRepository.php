<?php

namespace OrderManagementApi\Repository;

use OrderManagementApi\Model\Order;
use OrderManagementApi\Model\OrderItem;

class InMemoryOrderRepository implements OrderRepositoryInterface
{
    /** @var Order[] */
    private array $orders;

    public function __construct()
    {
        $this->orders = [
            new Order(
                1,
                '2025-06-20',
                'Objednávka 1',
                1500.0,
                'CZK',
                'new',
                [
                    new OrderItem('Notebook', 1500.0),
                ]
            ),
            new Order(
                2,
                '2025-06-19',
                'Objednávka 2',
                2000.0,
                'CZK',
                'paid',
                [
                    new OrderItem('Telefon', 2000.0),
                ]
            ),
        ];
    }

    public function findAll(): array
    {
        return $this->orders;
    }

    public function findById(int $id): ?Order
    {
        foreach ($this->orders as $order) {
            if ($order->toArray()['id'] === $id) {
                return $order;
            }
        }
        return null;
    }
}