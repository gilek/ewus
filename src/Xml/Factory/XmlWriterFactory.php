<?php

declare(strict_types=1);

namespace Gilek\Ewus\Xml\Factory;

use Sabre\Xml\Service;

class XmlWriterFactory
{
    /**
     * @param array<string, string> $namespaceMap
     */
    public function create(array $namespaceMap = []): Service
    {
        $service = new Service();
        $service->namespaceMap = $namespaceMap;

        return $service;
    }
}
