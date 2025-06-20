<?php

namespace OrderManagementApi\Controller;

use OrderManagementApi\Repository\OrderRepositoryInterface;

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
        echo json_encode($this->orderRepository->findAll());
    }

    public function show(int $id): void
    {
        header('Content-Type: application/json');
        $order = $this->orderRepository->findById($id);
        if ($order) {
            echo json_encode($order);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Order not found']);
        }
    }
}