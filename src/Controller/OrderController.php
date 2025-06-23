<?php

namespace OrderManagementApi\Controller;

use OrderManagementApi\Repository\OrderRepositoryInterface;
use OrderManagementApi\Exception\DatabaseException;

class OrderController
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(): void
    {
        header('Content-Type: application/json');

        try {
            echo json_encode(
                array_map(fn($order) => $order->toArray(), $this->orderRepository->findAll())
            );
        } catch (DatabaseException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function show(int $id): void
    {
        header('Content-Type: application/json');

        try {
            $order = $this->orderRepository->findByIdWithItems($id);

            if ($order) {
                echo json_encode($order->toArray());
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Order not found']);
            }
        } catch (DatabaseException $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}