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
}