<?php
declare(strict_types=1);

namespace Gilek\Ewus\Driver;

use Gilek\Ewus\Service\ServiceInterface;
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
    private function getInstance(string $url)
    {
        if (!array_key_exists($url, $this->instances)) {
            $this->instances[$url] = new SoapClient($url);
        }

        return $this->instances[$url];
    }    
    
    /**
     * {@inheritDoc}
     */
    public function doRequest(string $url, string $request): string
    {
        // TODO add throws to interface and handle this exception
        // WSDL can no be loaded exception
        return $this->getInstance($url)->__doRequest($request, $url, 'executeService', SOAP_1_1);
    }
}
