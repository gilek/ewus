<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

class XmlReaderFactory
{
    /**
     * @param string $xml
     * @param array<string, string> $namespaces
     *
     * @return XmlReader
     *
     * @throws EmptyResponseException
     * @throws InvalidResponseFormatException
     */
    public function create(string $xml, array $namespaces): XmlReader
    {
        return new XmlReader($xml, $namespaces);
    }
}