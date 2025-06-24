<?php

namespace OrderManagementApi\Http;

class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private mixed $body;
    private string $format = 'json';  // výchozí formát

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function setBody(mixed $body): self
    {
        $this->body = $body;
        return $this;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    public function send(): void
    {
        http_response_code($this->statusCode);

        if ($this->format === 'json') {
            $this->setHeader('Content-Type', 'application/json');
            $output = json_encode($this->body);
        } elseif ($this->format === 'xml') {
            $this->setHeader('Content-Type', 'application/xml');
            $output = $this->toXml($this->body);
        } else {
            $this->setHeader('Content-Type', 'text/plain');
            $output = (string)$this->body;
        }

        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        echo $output;
    }

    private function toXml($data, $rootElement = 'response'): string
    {
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\"?><{$rootElement}></{$rootElement}>");
        $this->arrayToXml($data, $xml);
        return $xml->asXML();
    }

    private function arrayToXml($data, \SimpleXMLElement &$xml): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild(is_numeric($key) ? "item{$key}" : $key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xml->addChild(is_numeric($key) ? "item{$key}" : $key, htmlspecialchars((string)$value));
            }
        }
    }
}