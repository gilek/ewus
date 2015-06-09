<?php

namespace gilek\ewus\drivers;

use gilek\ewus\services\Service;
use gilek\ewus\exceptions\Exception;

class SoapDriver implements Driver {
    
    /**
     *
     * @var \SoapClient[]
     */
    private $serviceInstances = [];   
    
    /**
     *
     * @var Service
     */
    private $service;    
    
    /**
     * 
     */
    public function __construct() {
        $this->checkExtensionStatus();
    }
    
    /**
     * 
     * @throws Exception
     */
    private function checkExtensionStatus() {
        if (!extension_loaded('soap')) {
            throw new Exception('Brak zainstalowanego rozszerzenia SOAP.');
        }
    }    
        
    /**
     * 
     * @inheritdoc
     */
    public function getService() {
        return $this->service;
    }

    /**
     * 
     * @inheritdoc
     */
    public function setService(Service $service) {
        $this->service = $service;
    }

    /**
     * 
     * @param Service $service
     * @return \SoapClient
     */
    public function getServiceInstance(Service $service) {
        $hash = spl_object_hash($service);
        if (!array_key_exists($hash, $this->serviceInstances)) {
            $this->serviceInstances[$hash] = new \SoapClient($service->getUrl());
        }
        return $this->serviceInstances[$hash];
    }    
    
    /**
     * 
     * @inheritdoc
     */
    public function sendXml($xml) {
        $soap = $this->getServiceInstance($this->service);
        return $soap->__doRequest($xml, $this->service->getUrl(), 'executeService', SOAP_1_1);        
    }
}