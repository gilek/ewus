<?php
declare(strict_types=1);

namespace Gilek\Ewus\Driver;

use Gilek\Ewus\Service\ServiceInterface;

interface DriverInterface
{
    /**
     * @param string $url
     * @param string $request XML format
     *
     * @return string
     */
    public function doRequest(string $url, string $request): string;
}
