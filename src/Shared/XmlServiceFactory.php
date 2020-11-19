<?php

declare(strict_types = 1);

namespace Gilek\Ewus\Shared;

use Sabre\Xml\Service;

/** Class XmlServiceFactory */
class XmlServiceFactory
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
