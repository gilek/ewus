<?php

declare(strict_types=1);

namespace Gilek\Ewus\Xml\Factory;

use Gilek\Ewus\Response\Exception\EmptyResponseException;
use Gilek\Ewus\Response\Exception\InvalidResponseContentException;
use Gilek\Ewus\Xml\XmlReader;

class XmlReaderFactory
{
    /**
     * @param string $xml
     * @param array<string, string> $namespaces
     *
     * @return XmlReader
     *
     * @throws EmptyResponseException
     * @throws InvalidResponseContentException
     */
    public function create(string $xml, array $namespaces): XmlReader
    {
        return new XmlReader($xml, $namespaces);
    }
}
