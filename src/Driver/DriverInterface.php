<?php
declare(strict_types=1);

namespace Gilek\Ewus\Driver;

interface DriverInterface
{
    /**
     * @param string $url
     * @param string $request XML format
     *
     * @return string
     *
     * @throws WsdlNotFoundException
     */
    public function doRequest(string $url, string $request): string;
}
