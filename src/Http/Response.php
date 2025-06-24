<?php

namespace OrderManagementApi\Http;

/**
 * Třída reprezentující HTTP odpověď.
 */
class Response
{
    /**
     * HTTP status kód odpovědi.
     *
     * @var int
     */
    private int $statusCode = 200;

    /**
     * Hlavičky HTTP odpovědi.
     *
     * @var array<string,string>
     */
    private array $headers = [];

    /**
     * Tělo odpovědi, libovolný datový typ.
     *
     * @var mixed
     */
    private mixed $body;

    /**
     * Formát výstupu (json, xml, text).
     *
     * @var string
     */
    private string $format = 'json';  // výchozí formát

    /**
     * Nastaví HTTP status kód odpovědi.
     *
     * @param int $code HTTP status kód
     * @return $this
     */
    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Nastaví HTTP hlavičku odpovědi.
     *
     * @param string $name Název hlavičky
     * @param string $value Hodnota hlavičky
     * @return $this
     */
    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Nastaví tělo odpovědi.
     *
     * @param mixed $body Data těla odpovědi
     * @return $this
     */
    public function setBody(mixed $body): self
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Nastaví formát výstupu odpovědi.
     *
     * @param string $format Formát výstupu (json, xml, text)
     * @return $this
     */
    public function setFormat(string $format): self
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Odešle HTTP odpověď klientovi.
     *
     * @return void
     */
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

    /**
     * Převod pole nebo objektu na XML string.
     *
     * @param mixed $data Data k převodu
     * @param string $rootElement Kořenový element XML
     * @return string XML reprezentace dat
     */
    private function toXml($data, string $rootElement = 'response'): string
    {
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\"?><{$rootElement}></{$rootElement}>");
        $this->arrayToXml($data, $xml);
        return $xml->asXML();
    }

    /**
     * Rekurzivní pomocná metoda pro převod pole na XML elementy.
     *
     * @param mixed $data Data k převodu
     * @param \SimpleXMLElement $xml XML element, do kterého se vkládá
     * @return void
     */
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