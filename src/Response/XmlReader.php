<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

use DOMDocument;
use DOMNode;
use DOMXPath;
use Gilek\Ewus\Exception\ResponseException;

class XmlReader
{
    /** @var DOMXPath */
    private $xpath;

    /**
     * @param string $xml
     * @param array<string, string> $namespaces
     */
    public function __construct(string $xml, array $namespaces)
    {
        $this->xpath = $this->createXpath($xml, $namespaces);
    }

    /**
     * @param string $xml
     * @param array<string, string> $namespaces
     * @return DOMXPath
     */
    private function createXpath(string $xml, array $namespaces): DOMXPath
    {
        // TODO micro perf
        if (strlen($xml) === 0) {
            // TODO throw new ResponseException('Brak odpowiedzi na żądanie.');
        }

        $document = new DOMDocument();
        try {
            $document->loadXML($xml);
        } catch (\Exception $e) {
            // TODO
            //throw new ResponseException('Nieprawidłowy format odpowiedzi.');
        }

        $xpath = new DOMXPath($document);
        foreach ($namespaces as $prefix => $namespace) {
            $xpath->registerNamespace($prefix, $namespace);
        }

        return $xpath;
    }

    /**
     * @param string $query
     *
     * @param int $index
     * @return DOMNode
     *
     * @throws \RuntimeException
     */
    public function getElement(string $query, int $index = 0): DOMNode
    {
        $elements = $this->xpath->query($query);
        if ($elements->length === 0) {
            // TODO
            throw new \RuntimeException('TODO');
        }

        return $elements->item($index);
    }

    /**
     * @param string $query
     *
     * @param int $index
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getElementValue(string $query, int $index = 0): string
    {
        return $this->getElement($query, $index)->nodeValue;
    }

    /**
     * @param string $query
     * @param string $attribute
     * @param int $index
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getElementAttribute(string $query, string $attribute, int $index = 0): string
    {
        $element = $this->getElement($query, $index);

        return $element->getAttribute($attribute);
    }
}