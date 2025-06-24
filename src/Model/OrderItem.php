<?php

namespace OrderManagementApi\Model;

class OrderItem implements \JsonSerializable
{
    /**
     * Název produktu
     * @var string
     */
    private string $productName;

    /**
     * Cena za kus produktu
     * @var float
     */
    private float $price;

    /**
     * Množství produktu v položce objednávky
     * @var int
     */
    private int $quantity;

    /**
     * Konstruktor položky objednávky
     *
     * @param string $productName Název produktu
     * @param float $price Cena za kus
     * @param int $quantity Množství (výchozí 1)
     */
    public function __construct(string $productName, float $price, int $quantity = 1)
    {
        $this->productName = $productName;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    /**
     * Vrátí položku objednávky jako asociativní pole
     *
     * @return array{
     *   product_name: string,
     *   price: float,
     *   quantity: int
     * }
     */
    public function toArray(): array
    {
        return [
            'product_name' => $this->productName,
            'price' => $this->price,
            'quantity' => $this->quantity,
        ];
    }

    /**
     * Specifikace JSON serializace
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}