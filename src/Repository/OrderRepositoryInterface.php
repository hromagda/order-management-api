<?php

namespace OrderManagementApi\Repository;

use OrderManagementApi\Model\Order;

interface OrderRepositoryInterface
{
    /**
     * @return Order[]
     */
    public function findAll(): array;

    public function findById(int $id): ?Order;
}