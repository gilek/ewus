<?php
declare(strict_types=1);

namespace Gilek\Ewus\Operation;

use DOMDocument;
use DOMXpath;
use Gilek\Ewus\Driver\DriverInterface;
use Gilek\Ewus\Exception\ResponseException;
use Gilek\Ewus\Response\Response;
use Gilek\Ewus\Response\SessionInterface;

// TODO baseOperation is the evil
abstract class BaseOperation implements OperationInterface
{
    /** @var DriverInterface */
    private $driver;
    
    /** @var SessionInterface */
    private $session;

    /**
     * @return DriverInterface
     */
    public function getDriver(): DriverInterface
    {
        return $this->driver;
    }

    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    /**
     * @param DriverInterface $driver
     */
    public function setDriver(DriverInterface $driver): void
    {
        $this->driver = $driver;
    }

    /**
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    public function run()
    {
        $responseXml = $this->getDriver()->doRequest($this->makeRequestXml());
        $dom = $this->parseResponse($responseXml);
        $response = $this->makeResponse($dom);
        $response->setResponseXml($responseXml);
        $response->setOperation($this);

        return $response;
    }    
  
    /**
     * @param string $xml
     *
     * @return DOMDocument
     *
     * @throws ResponseException
     */
    private function parseResponse(string $xml): DOMDocument
    {
        // TODO this should be separate service

        if (strlen($xml) === 0) {
            throw new ResponseException('Brak odpowiedzi na żądanie.');
        }

        $doc = new DOMDocument();
        try {
            $doc->loadXML($xml);
        } catch (\Exception $e) {
            throw new ResponseException('Nieprawidłowy format odpowiedzi.');
        }

        $xpath = new DOMXpath($doc);
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

    /**
     * @return string
     */
    abstract function makeRequestXml(): string;

    /**
     * @param DOMDocument $dom
     *
     * @return Response
     */
    abstract function makeResponse(DOMDocument $dom): Response;
}
