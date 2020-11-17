<?php
declare(strict_types=1);

namespace Gilek\Ewus\Driver;

use Gilek\Ewus\Service\ServiceInterface;
use SoapClient;
use SoapFault;

class SoapDriver implements DriverInterface
{
    /** @var SoapClient[] */
    private $serviceInstances = [];   
    
    /** @var ServiceInterface */
    private $service;
        
    /**
     * {@inheritDoc}
     */
    public function getService(): ServiceInterface
    {
        return $this->service;
    }

    /**
     * {@inheritDoc}
     */
    public function setService(ServiceInterface $service): void
    {
        $this->service = $service;
    }

    /**
     * @param ServiceInterface $service
     *
     * @return SoapClient
     *
     * TODO handle exception
     * @throws SoapFault
     */
    private function getServiceInstance(ServiceInterface $service)
    {
        $hash = spl_object_hash($service);
        if (!array_key_exists($hash, $this->serviceInstances)) {
            $this->serviceInstances[$hash] = new SoapClient($service->getUrl());
        }

        return $this->serviceInstances[$hash];
    }    
    
    /**
     * {@inheritDoc}
     */
    public function sendXml(string $xml): string
    {
        $soap = $this->getServiceInstance($this->service);

        return $soap->__doRequest($xml, $this->service->getUrl(), 'executeService', SOAP_1_1);        
    }
}
