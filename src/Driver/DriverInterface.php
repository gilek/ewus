<?php
declare(strict_types=1);

namespace Gilek\Ewus\Driver;

use Gilek\Ewus\Driver\Exception\SoapOperationException;

interface DriverInterface
{
    /**
     * @param string $url
     * @param string $request XML format
     *
     * @return string
     *
     * @throws SoapOperationException
     */
    public function doRequest(string $url, string $request): string;
}
