<?php

namespace PetrK\OrderManagementApi\Repository;

use PetrK\OrderManagementApi\Model\Order;

interface OrderRepositoryInterface
{
    /**
     * @return Order[]
     */
    public function findAll(): array;

    public function findById(int $id): ?Order;
}