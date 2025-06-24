<?php

namespace OrderManagementApi\Http;

/**
 * Třída reprezentující HTTP požadavek.
 */
class Request
{
    /**
     * HTTP metoda požadavku (GET, POST, atd.).
     *
     * @var string
     */
    private string $method;

    /**
     * URI požadavku (cesta bez query stringu).
     *
     * @var string
     */
    private string $uri;

    /**
     * Konstruktor, načte metodu a URI z aktuálního PHP prostředí.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
    }

    /**
     * Vrátí HTTP metodu požadavku.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Vrátí URI požadavku.
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}