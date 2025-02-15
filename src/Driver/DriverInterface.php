<?php

declare(strict_types=1);

namespace Gilek\Ewus\Driver;

use Gilek\Ewus\Driver\Exception\SoapOperationFailedException;

interface DriverInterface
{
    /**
     * @throws SoapOperationFailedException
     */
    public function doRequest(string $url, string $request): string;
}
