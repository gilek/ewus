<?php
declare(strict_types=1);

namespace Gilek\Ewus\Driver;

use Gilek\Ewus\Service\ServiceInterface;

interface DriverInterface
{
    /**
     * TODO better name
     * @param string $xml
     *
     * @return string
     */
    public function sendXml(string $xml): string;

    /**
     * @param ServiceInterface $service
     */
    public function setService(ServiceInterface $service): void;

    /**
     * @return ServiceInterface
     */
    public function getService(): ServiceInterface;
}
