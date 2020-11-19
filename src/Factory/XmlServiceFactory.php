<?php

declare(strict_types = 1);

namespace Gilek\Ewus\Factory;

use Sabre\Xml\Service;

/** Class XmlServiceFactory */
class XmlServiceFactory
{
    /**
     * @param array<string, string> $namespaceMap
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
