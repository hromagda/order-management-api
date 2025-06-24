<?php

namespace OrderManagementApi\Http;

class Request
{
    private string $method;
    private string $uri;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}