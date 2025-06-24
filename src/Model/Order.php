<?php

namespace OrderManagementApi\Model;

class Order implements \JsonSerializable
{
    private int $id;
    private string $date;
    private string $description;
    private float $total;
    private string $currency;
    private string $status;

    /** @var OrderItem[] */
    private array $items;

    public function __construct(
        int $id,
        string $date,
        string $description,
        float $total,
        string $currency,
        string $status,
        array $items = []
    ) {
        $this->id = $id;
        $this->date = $date;
        $this->description = $description;
        $this->total = $total;
        $this->currency = $currency;
        $this->status = $status;
        $this->items = $items;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'description' => $this->description,
            'total' => $this->total,
            'currency' => $this->currency,
            'status' => $this->status,
            'items' => array_map(fn($item) => $item->toArray(), $this->items),
        ];
    }

public function getId(): int
{
    return $this->id;
}

    public function getItems(): array
    {
        return $this->items;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}