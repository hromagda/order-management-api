<?php

namespace OrderManagementApi\Model;

class OrderItem implements \JsonSerializable
{
    private string $productName;
    private float $price;
    private int $quantity;

    public function __construct(string $productName, float $price, int $quantity = 1)
    {
        $this->productName = $productName;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function toArray(): array
    {
        return [
            'product_name' => $this->productName,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}