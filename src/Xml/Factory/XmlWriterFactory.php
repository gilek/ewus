<?php

declare(strict_types=1);

namespace Gilek\Ewus\Xml\Factory;

use Sabre\Xml\Service;

class XmlWriterFactory
{
    /**
     * @param array<string, mixed> $namespaceMap
     *
     * @return Service
     */
    public function create($namespaceMap = [])
    {
        $service = new Service();
        $service->namespaceMap = $namespaceMap;

        return $service;
    }
}
