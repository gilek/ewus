<?php

declare(strict_types=1);

namespace Gilek\Ewus\Xml\Factory;

use Sabre\Xml\Service;

class XmlWriterFactory
{
    /**
     * @param array<string, mixed> $namespaceMap
     */
    public function create($namespaceMap = []): Service
    {
        $service = new Service();
        $service->namespaceMap = $namespaceMap;

        return $service;
    }
}
