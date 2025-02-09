<?php

declare(strict_types=1);

namespace Gilek\Ewus\Driver;

use Gilek\Ewus\Driver\Exception\SoapOperationFailedException;
use nusoap_client;

class NusoapDriver implements DriverInterface
{
    /** @var nusoap_client[] */
    private array $instances = [];

    private function getInstance(string $url): nusoap_client
    {
        if (!array_key_exists($url, $this->instances)) {
            $this->instances[$url] = $this->createInstance($url);
        }

        return $this->instances[$url];
    }

    private function createInstance(string $url): nusoap_client
    {
        return new nusoap_client($url, 'wsdl');
    }

    #[\Override]
    public function doRequest(string $url, string $request): string
    {
        $client = $this->getInstance($url);
        if ($client->send($request) === false) {
            throw new SoapOperationFailedException($client->getError());
        }

        return $client->responseData;
    }
}
