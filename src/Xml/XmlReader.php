<?php

declare(strict_types=1);

namespace Gilek\Ewus\Xml;

use DOMDocument;
use DOMElement;
use DOMNode;
use DOMNodeList;
use DOMXPath;
use Gilek\Ewus\Response\Exception\EmptyResponseException;
use Gilek\Ewus\Response\Exception\InvalidResponseContentException;
use Gilek\Ewus\Xml\Exception\ElementNotFoundException;
use Gilek\Ewus\Xml\Exception\NamespaceNotRegisteredException;

class XmlReader
{
    private readonly DOMXPath $xpath;
    /** @var array<string, string> */
    private readonly array $namespacePrefixes;

    /**
     * @param array<string, string> $namespaces
     *
     * @throws EmptyResponseException
     * @throws InvalidResponseContentException
     */
    public function __construct(string $xml, array $namespaces = [])
    {
        $this->xpath = $this->createXpath($xml);
        $this->registerNamespaces($namespaces);
    }

    /**
     * @throws EmptyResponseException
     * @throws InvalidResponseContentException
     */
    private function createXpath(string $xml): DOMXPath
    {
        if (trim($xml) === '') {
            throw new EmptyResponseException('Empty response received.');
        }

        return new DOMXPath(
            $this->createDomDocument($xml)
        );
    }

    /**
     * @param array<string, string> $namespaces
     */
    private function registerNamespaces(array $namespaces): void
    {
        foreach ($namespaces as $prefix => $namespace) {
            $this->registerNamespace($prefix, $namespace);
        }
    }

    public function registerNamespace(string $prefix, string $namespace): void
    {
        $this->xpath->registerNamespace($prefix, $namespace);
        $this->namespacePrefixes[$namespace] = $prefix;
    }

    /**
     * @throws NamespaceNotRegisteredException
     */
    public function getNamespacePrefix(string $namespace): string
    {
        if (!array_key_exists($namespace, $this->namespacePrefixes)) {
            throw new NamespaceNotRegisteredException(
                sprintf('Namespace "%s" is not registered.', $namespace)
            );
        }

        return $this->namespacePrefixes[$namespace];
    }

    /**
     * @throws InvalidResponseContentException
     */
    private function createDomDocument(string $xml): DOMDocument
    {
        set_error_handler(function (int $number, string $error, string $file, int $line, array $context): bool {
            if (preg_match('/^DOMDocument::loadXML\(\): (.+)$/', $error, $m) === 1) {
                throw new InvalidResponseContentException($m[1]);
            }

            return false;
        });
        $document = new DOMDocument();
        $document->loadXML($xml);
        restore_error_handler();

        return $document;
    }

    /**
     * @throws ElementNotFoundException
     *
     * @return DOMNodeList<DOMNode>
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
     * @throws ElementNotFoundException
     */
    public function getElement(string $query, int $index = 0): DOMNode
    {
        $elements = $this->xpath->query($query);
        if ($elements === false || $elements->length === 0) {
            throw new ElementNotFoundException(sprintf('Element "%s" at index %d not found.', $query, $index));
        }

        $element = $elements->item($index);
        if ($element === null) {
            throw new ElementNotFoundException(sprintf('Element "%s" at index %d not found.', $query, $index));
        }

        return $element;
    }

    public function hasElement(string $query): bool
    {
        try {
            $this->getElement($query);

            return true;
        } catch (ElementNotFoundException) {
            return false;
        }
    }

    /**
     * @throws ElementNotFoundException
     */
    public function getElementValue(string $query, int $index = 0): string
    {
        return $this->getElement($query, $index)->nodeValue;
    }

    /**
     * @throws ElementNotFoundException
     */
    public function getElementAttribute(string $query, string $attribute, int $index = 0): string
    {
        /** @var DOMElement $element */
        $element = $this->getElement($query, $index);

        return $element->getAttribute($attribute);
    }
}
