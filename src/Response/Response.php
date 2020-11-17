<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response;

use Gilek\Ewus\Operation\OperationInterface;

abstract class Response 
{
    /** @var OperationInterface */
    private $operation;
    
    /** @var string */
    private $responseXml;

    /**
     * TODO not used?
     * @return string
     */
    public function getResponseXml(): string
    {
        return $this->responseXml;
    }

    /**
     * TODO not used?
     * @param string $responseXml
     */
    public function setResponseXml(string $responseXml)
    {
        $this->responseXml = $responseXml;
    }
    
    /**
     * @return OperationInterface
     */
    public function getOperation(): OperationInterface
    {
        return $this->operation;
    }

    /**
     * @param OperationInterface $operation
     */
    public function setOperation(OperationInterface $operation): void
    {
        $this->operation = $operation;
    }
}
