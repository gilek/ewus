<?php
declare(strict_types=1);

namespace Gilek\Ewus\Driver;

use SoapClient;
use SoapFault;

class SoapDriver implements DriverInterface
{
    /** @var SoapClient[] */
    private $instances = [];

    /**
     * @param string $url
     *
     * @return SoapClient
     *
     * @throws SoapFault
     */
    private function getInstance(string $url): SoapClient
    {
        if (!array_key_exists($url, $this->instances)) {
            $this->instances[$url] = $this->createInstance($url);
        }

        return $this->instances[$url];
    }

    /**
     * @param string $url
     *
     * @return SoapClient
     *
     * @throws SoapFault
     */
    private function createInstance(string $url): SoapClient
    {
        $context = stream_context_create([
            'ssl' => [
                'ciphers' => 'DEFAULT@SECLEVEL=1',
            ],
        ]);

        return new SoapClient($url, ['stream_context' => $context]);
    }
    
    /**
     * {@inheritDoc}
     */
    public function doRequest(string $url, string $request): string
    {
        try {
            return $this->getInstance($url)->__doRequest($request, $url, 'executeService', SOAP_1_1);
        } catch (SoapFault $exception) {
            throw new WsdlNotFoundException(sprintf('Couldn\'t load WSDL from "%s".', $url), 0, $exception);
        }
    }
}
