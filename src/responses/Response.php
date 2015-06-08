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
    function getResponseXml() {
        return $this->responseXml;
    }

    /**
     * 
     * @param string $responseXml
     */
    function setResponseXml($responseXml) {
        $this->responseXml = $responseXml;
    }
    
    /**
     * 
     * @return Operation
     */
    function getOperation() {
        return $this->operation;
    }

    /**
     * 
     * @param Operation $operation
     */
    function setOperation(Operation $operation) {
        $this->operation = $operation;
    }
}