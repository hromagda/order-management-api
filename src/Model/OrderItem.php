<?php

namespace PetrK\OrderManagementApi\Model;

class OrderItem
{
    private string $name;
    private float $amount;

    public function __construct(string $name, float $amount)
    {
        $this->name = $name;
        $this->amount = $amount;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'amount' => $this->amount,
        ];
    }
}