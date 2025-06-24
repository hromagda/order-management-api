<?php

namespace OrderManagementApi\Repository;

use OrderManagementApi\Model\Order;

interface OrderRepositoryInterface
{
    /**
     * Vrátí všechny objednávky.
     *
     * @return Order[] Pole všech objednávek.
     */
    public function findAll(): array;

    /**
     * Najde objednávku podle ID.
     *
     * @param int $id ID objednávky.
     * @return Order|null Vrací objednávku, pokud existuje, nebo null.
     */
    public function findById(int $id): ?Order;

    /**
     * Najde objednávku podle ID včetně položek.
     *
     * @param int $id ID objednávky.
     * @return Order|null Vrací objednávku včetně položek, nebo null, pokud není nalezena.
     */
    public function findByIdWithItems(int $id): ?Order;
}