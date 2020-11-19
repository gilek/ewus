<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

class XmlReaderFactory
{
    public function create(string $xml, array $namespaces): XmlReader
    {
        return new XmlReader($xml, $namespaces);
    }
}