<?php

namespace gilek\ewus\drivers;

use gilek\ewus\services\Service;

interface Driver {
    /**
     * 
     * @param string $xml
     */
    public function sendXml($xml);
    
    /**
     * 
     * @param Service $service 
     */
    public function setService(Service $service);
    
    /**
     * 
     * @return Service
     */
    public function getService();
}