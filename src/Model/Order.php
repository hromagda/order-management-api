<?php

namespace PetrK\OrderManagementApi\Model;

class Order
{
    private int $id;
    private string $createdAt;
    private string $name;
    private float $amount;
    private string $currency;
    private string $status;
    /** @var OrderItem[] */
    private array $items;

    public function __construct(
        int $id,
        string $createdAt,
        string $name,
        float $amount,
        string $currency,
        string $status,
        array $items
    ) {
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->name = $name;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->status = $status;
        $this->items = $items;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->createdAt,
            'name' => $this->name,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'status' => $this->status,
            'items' => array_map(fn($item) => $item->toArray(), $this->items),
        ];
    }
}