<?php

namespace OrderManagementApi\Repository;

use OrderManagementApi\Model\Order;
use OrderManagementApi\Model\OrderItem;

class ApiOrderRepository implements OrderRepositoryInterface
{
    private string $apiUrl;

    public function __construct(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

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

    public function findByIdWithItems(int $id): ?Order
    {
        return $this->findById($id);
    }
}