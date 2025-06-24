<?php

namespace OrderManagementApi\Controller;

use OrderManagementApi\Repository\OrderRepositoryInterface;
use OrderManagementApi\Exception\DatabaseException;
use OrderManagementApi\Http\Request;
use OrderManagementApi\Http\Response;

class OrderController
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $req, Response $res): void
    {
        try {
            $orders = array_map(fn($o) => $o->toArray(), $this->orderRepository->findAll());
            $res->setBody($orders);
        } catch (DatabaseException $e) {
            $res->setStatusCode(500)
                ->setBody(['error' => $e->getMessage()]);
        }
    }

    public function show(int $id, Request $req, Response $res): void
    {
        try {
            $order = $this->orderRepository->findByIdWithItems($id);
            if ($order) {
                $res->setBody($order->toArray());
            } else {
                $res->setStatusCode(404)
                    ->setBody(['error' => 'Order not found']);
            }
        } catch (DatabaseException $e) {
            $res->setStatusCode(500)
                ->setBody(['error' => $e->getMessage()]);
        }
    }
}