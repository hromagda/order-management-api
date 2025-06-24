<?php

namespace OrderManagementApi\Repository;

use OrderManagementApi\Model\Order;
use OrderManagementApi\Model\OrderItem;

class InMemoryOrderRepository implements OrderRepositoryInterface
{
    /**
     * Pole objednávek uložených v paměti
     *
     * @var Order[]
     */
    private array $orders;

    /**
     * Konstruktor, inicializuje demo data
     */
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
                    new OrderItem('Notebook', 1500.0)
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
                    new OrderItem('Telefon', 2000.0)
                ]
            ),
            new Order(
                3,
                '2024-06-02',
                'Objednávka bez položek',
                0.0,
                'CZK',
                'pending',
                []
            )
        ];
    }

    /**
     * Vrátí všechny objednávky
     *
     * @return Order[] Pole objednávek
     */
    public function findAll(): array
    {
        return $this->orders;
    }

    /**
     * Najde objednávku podle ID
     *
     * @param int $id ID objednávky
     * @return Order|null Objednávka nebo null, pokud není nalezena
     */
    public function findById(int $id): ?Order
    {
        foreach ($this->orders as $order) {
            if ($order->toArray()['id'] === $id) {
                return $order;
            }
        }
        return null;
    }

    /**
     * Najde objednávku včetně položek podle ID
     *
     * V InMemory repository vrací totéž jako findById, protože položky jsou již součástí objektu.
     *
     * @param int $id ID objednávky
     * @return Order|null Objednávka nebo null, pokud není nalezena
     */
    public function findByIdWithItems(int $id): ?Order
    {
        return $this->findById($id);
    }
}