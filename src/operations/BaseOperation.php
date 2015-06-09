<?php

namespace gilek\ewus\operations;

use gilek\ewus\drivers\Driver;
use gilek\ewus\exceptions\ResponseException;
use gilek\ewus\responses\Session;

abstract class BaseOperation implements Operation
{
    /**
     *
     * @var Driver 
     */
    private $driver;
    
    /**
     *
     * @var Session 
     */
    private $session;

    /**
     * 
     * @return Driver
     */
    public function getDriver() {
        return $this->driver;
    }

    /**
     * 
     * @return Session
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * 
     * @param Driver $driver
     */
    public function setDriver(Driver $driver) {
        $this->driver = $driver;
    }

    /**
     * 
     * @param Session $session
     */
    public function setSession(Session $session) {
        $this->session = $session;
    }

    /**
     * 
     * @inheritdoc
     */
    public function run() {
        $responseXml = $this->getDriver()->sendXml($this->makeRequestXml());
        $dom = $this->parseResponse($responseXml);
        $response = $this->makeResponse($dom);
        $response->setResponseXml($responseXml);
        $response->setOperation($this);
        return $response;
    }    
  
    /**
     * 
     * @param string $xml
     * @return \DOMDocument
     * @throws ResponseException
     */
    private function parseResponse($xml) {        

        if (strlen($xml) === 0) {
            throw new ResponseException('Brak odpowiedzi na żądanie.');
        }

        $doc = new \DOMDocument();
        try {
            $doc->loadXML($xml);
        } catch (\Exception $e) {
            throw new ResponseException('Nieprawidłowy format odpowiedzi.');
        }

        $xpath = new \DOMXpath($doc);
        $xpath->registerNamespace('env', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xpath->registerNamespace('xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xpath->registerNamespace('com', 'http://xml.kamsoft.pl/ws/common');

        $fault = $xpath->query('//env:Fault');

        if ($fault->length === 1) {
            $e = new ResponseException();
            $com = $xpath->query('//com:*');
            if ($com->length >= 1) {
                
                $type = $com->item(0)->getAttribute("xsi:type");
                $type = mb_substr($type, mb_strpos($type, ':') + 1);
                $e->setType($type);

                $com = $xpath->query('//com:faultstring');
                if ($com->length === 1) {
                    $e->setMessage($com->item(0)->nodeValue);
                }
            }
            throw $e;
        }

        return $doc;        
    }
    
    abstract function makeRequestXml();
    abstract function makeResponse(\DOMDocument $dom);    
}