<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

use DOMDocument;
use DOMNode;
use DOMNodeList;
use DOMXPath;

class XmlReader
{
    /** @var DOMXPath */
    private $xpath;

    /**
     * @param string $xml
     * @param array<string, string> $namespaces
     *
     * @throws EmptyResponseException
     * @throws InvalidResponseFormatException
     */
    public function __construct(string $xml, array $namespaces)
    {
        $this->xpath = $this->createXpath($xml, $namespaces);
    }

    /**
     * @param string $xml
     * @param array<string, string> $namespaces
     *
     * @return DOMXPath
     *
     * @throws EmptyResponseException
     * @throws InvalidResponseFormatException
     */
    private function createXpath(string $xml, array $namespaces): DOMXPath
    {
        if (trim($xml) === '') {
            throw new EmptyResponseException('Empty response received.');
        }

        $xpath = new DOMXPath(
            $this->createDomDocument($xml)
        );
        foreach ($namespaces as $prefix => $namespace) {
            $xpath->registerNamespace($prefix, $namespace);
        }

        return $xpath;
    }

    /**
     * @param string $xml
     * @return DOMDocument
     *
     * @throws InvalidResponseFormatException
     */
    private function createDomDocument(string $xml): DOMDocument
    {
        set_error_handler(function($number, $error){
            if (preg_match('/^DOMDocument::loadXML\(\): (.+)$/', $error, $m) === 1) {
                throw new InvalidResponseFormatException($m[1]);
            }
        });
        $document = new DOMDocument();
        $document->loadXML($xml);
        restore_error_handler();

        return $document;
    }

    /**
     * @param string $query
     *
     * @return DOMNodeList
     *
     * @throws ElementNotFoundException
     */
    public function getElements(string $query): DOMNodeList
    {
        $elements = $this->xpath->query($query);
        if ($elements === false) {
            throw new ElementNotFoundException(sprintf('Elements "%s" were not found.', $query));
        }

        return $elements;
    }

    /**
     * @param string $query
     * @param int $index
     *
     * @return DOMNode // TODO better to wrap it somehow, that missing attribute will thwon an exception
     *
     * @throws ElementNotFoundException
     */
    public function getElement(string $query, int $index = 0): DOMNode
    {
        $elements = $this->xpath->query($query);
        if ($elements->length === 0) {
            throw new ElementNotFoundException(sprintf('Element "%s" at index %d not found.', $query, $index));
        }

        return $elements->item($index);
    }

    /**
     * @param string $query
     * @param int $index
     *
     * @return string
     *
     * @throws ElementNotFoundException
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
     * @throws ElementNotFoundException
     */
    public function getElementAttribute(string $query, string $attribute, int $index = 0): string
    {
        $element = $this->getElement($query, $index);

        return $element->getAttribute($attribute);
    }
}
