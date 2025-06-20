<?php

namespace OrderManagementApi\Model;

class OrderItem implements \JsonSerializable
{
    private string $productName;
    private float $price;

    public function __construct(string $productName, float $price)
    {
        $this->productName = $productName;
        $this->price = $price;
    }

    public function jsonSerialize(): array
    {
        return [
            'productName' => $this->productName,
            'price' => $this->price,
        ];
    }
}