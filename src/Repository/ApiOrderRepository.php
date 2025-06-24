<?php

namespace OrderManagementApi\Repository;

use OrderManagementApi\Model\Order;
use OrderManagementApi\Model\OrderItem;

class ApiOrderRepository implements OrderRepositoryInterface
{
    /**
     * URL API endpointu
     * @var string
     */
    private string $apiUrl;

    /**
     * Konstruktor repozitáře
     *
     * @param string $apiUrl Základní URL API
     */
    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * Vrátí všechny objednávky načtené z API
     *
     * @return Order[] Pole objektů objednávek
     */
    public function findAll(): array
    {
        $json = file_get_contents($this->apiUrl . '/orders');
        $data = json_decode($json, true);

        $orders = [];
        foreach ($data as $orderData) {
            $items = array_map(fn($item) => new OrderItem($item['name'], $item['price']), $orderData['items']);
            $orders[] = new Order(
                $orderData['id'],
                $orderData['date'],
                $orderData['description'],
                $orderData['totalPrice'],
                $orderData['currency'],
                $orderData['status'],
                $items
            );
        }
        return $orders;
    }

    /**
     * Najde objednávku podle ID
     *
     * @param int $id ID objednávky
     * @return Order|null Vrátí objednávku nebo null, pokud nebyla nalezena
     */
    public function findById(int $id): ?Order
    {
        $json = @file_get_contents($this->apiUrl . "/orders/{$id}");
        if ($json === false) {
            return null;
        }
        $orderData = json_decode($json, true);
        $items = array_map(fn($item) => new OrderItem($item['name'], $item['price']), $orderData['items']);
        return new Order(
            $orderData['id'],
            $orderData['date'],
            $orderData['description'],
            $orderData['totalPrice'],
            $orderData['currency'],
            $orderData['status'],
            $items
        );
    }

    /**
     * Najde objednávku podle ID včetně položek
     *
     * @param int $id ID objednávky
     * @return Order|null
     */
    public function findByIdWithItems(int $id): ?Order
    {
        return $this->findById($id);
    }
}