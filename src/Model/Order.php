<?php

namespace OrderManagementApi\Model;

class Order implements \JsonSerializable
{
    /**
     * ID objednávky
     * @var int
     */
    private int $id;

    /**
     * Datum objednávky ve formátu YYYY-MM-DD
     * @var string
     */
    private string $date;

    /**
     * Popis objednávky
     * @var string
     */
    private string $description;

    /**
     * Celková částka objednávky
     * @var float
     */
    private float $total;

    /**
     * Měna částky (např. CZK, EUR)
     * @var string
     */
    private string $currency;

    /**
     * Stav objednávky (např. new, processing, completed)
     * @var string
     */
    private string $status;

    /**
     * Položky objednávky
     * @var OrderItem[]
     */
    private array $items;

    /**
     * Konstruktor objednávky
     *
     * @param int $id
     * @param string $date
     * @param string $description
     * @param float $total
     * @param string $currency
     * @param string $status
     * @param OrderItem[] $items
     */
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

    /**
     * Vrátí objednávku jako asociativní pole
     *
     * @return array{
     *   id: int,
     *   date: string,
     *   description: string,
     *   total: float,
     *   currency: string,
     *   status: string,
     *   items: array
     * }
     */
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

    /**
     * Vrátí ID objednávky
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Vrátí položky objednávky
     *
     * @return OrderItem[]
     */
    public function getItems(): array
    {
        return $this->items;
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