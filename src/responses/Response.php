<?php

namespace gilek\ewus\responses;

use gilek\ewus\operations\Operation;

abstract class Response 
{
    /**
     *
     * @var Operation 
     */
    private $operation;
    
    /**
     *
     * @var string
     */
    private $responseXml;
    
    /**
     * 
     * @return string
     */
    public function getResponseXml() {
        return $this->responseXml;
    }

    /**
     * 
     * @param string $responseXml
     */
    public function setResponseXml($responseXml) {
        $this->responseXml = $responseXml;
    }
    
    /**
     * 
     * @return Operation
     */
    public function getOperation() {
        return $this->operation;
    }

    /**
     * 
     * @param Operation $operation
     */
    public function setOperation(Operation $operation) {
        $this->operation = $operation;
    }
}