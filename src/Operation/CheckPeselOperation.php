<?php
declare(strict_types=1);

namespace Gilek\Ewus\Operation;

use Gilek\Ewus\Exception\ResponseException;
use Gilek\Ewus\Response\CheckCwuResponse;

class CheckPeselOperation extends BaseOperation
{
    /** @var string */
    private $pesel;

    /**
     * @param string $pesel
     */
    public function __construct($pesel) {
        $this->setPesel($pesel);
    }
    
    /**
     * @return string
     */
    public function getPesel(): string
    {
        return $this->pesel;
    }

    /**
     * TODO THIS SHOULN'D EXIST
     * @param string $pesel
     */
    public function setPesel(string $pesel): void
    {
        $this->pesel = $pesel;
    }
    
    /**
     * {@inheritDoc}
     */    
    public function makeRequestXml(): string
    {
        return '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="http://xml.kamsoft.pl/ws/common" xmlns:brok="http://xml.kamsoft.pl/ws/broker">
           <soapenv:Header>
              <com:session id="' . $this->getSession()->getSessionId() . '" xmlns:ns1="http://xml.kamsoft.pl/ws/common"/>
              <com:authToken id="' . $this->getSession()->getToken() . '" xmlns:ns1="http://xml.kamsoft.pl/ws/common"/>
           </soapenv:Header>
           <soapenv:Body>
              <brok:executeService>
                 <com:location>
                    <com:namespace>nfz.gov.pl/ws/broker/cwu</com:namespace>
                    <com:localname>checkCWU</com:localname>
                    <com:version>3.0</com:version>
                 </com:location>
                 <brok:payload>
                    <brok:textload>
                       <ewus:status_cwu_pyt xmlns:ewus="https://ewus.nfz.gov.pl/ws/broker/ewus/status_cwu/v3">
                          <ewus:numer_pesel>' . $this->getPesel() . '</ewus:numer_pesel>
                          <ewus:system_swiad nazwa="EwusClient by gilek" wersja="1.1"/>
                       </ewus:status_cwu_pyt>
                    </brok:textload>
                 </brok:payload>
              </brok:executeService>
           </soapenv:Body>
        </soapenv:Envelope>';        
    }

    /**
     * {@inheritDoc}
     */    
    public function makeResponse(\DOMDocument $xml)
    {
        $xpath = new \DOMXpath($xml);
        $xpath->registerNamespace('odp', 'https://ewus.nfz.gov.pl/ws/broker/ewus/status_cwu/v3');

        $response = new CheckCwuResponse();

        $elements = $xpath->query("//odp:status_cwu_odp");
        if ($elements->length !== 1) {
            throw new ResponseException('Nie można pobrać informacji o id operacji.');
        }
        $response->setData($elements->item(0)->getAttribute('id_operacji'), CheckCwuResponse::DATA_OPERATION_ID);


        $elements = $xpath->query("//odp:status_cwu");
        if ($elements->length !== 1) {
            throw new ResponseException('Nie można pobrać informacji o statusie pacjenta.');
        }

        $status = $elements->item(0)->nodeValue;

        if ($status === '1') {
            $elements = $xpath->query("//odp:status_ubezp");
            if ($elements->length !== 1) {
                throw new ResponseException('Nie można pobrać informacji o uprawnieniu do świadczeń.');
            }
        }
        $response->setData($status === '1' ? $elements->item(0)->nodeValue : CheckCwuResponse::STATUS_NOT_EXIST, CheckCwuResponse::DATA_STATUS);

        if ($status === '1') {
            $elements = $xpath->query("//odp:imie");
            if ($elements->length !== 1) {
                throw new ResponseException('Nie można pobrać informacji o imieniu pacjenta.');
            }
        }
        $response->setData($status === '1' ? $elements->item(0)->nodeValue : null, CheckCwuResponse::DATA_PATIENT_NAME);

        if ($status === '1') {
            $elements = $xpath->query("//odp:nazwisko");
            if ($elements->length !== 1) {
                throw new ResponseException('Nie można pobrać informacji o nazwisko upacjenta.');
            }
        }
        $response->setData($status === '1' ? $elements->item(0)->nodeValue : null, CheckCwuResponse::DATA_PATIENT_SURNAME);

        if ($status === '1') {
            $elements = $xpath->query("//odp:id_ow");
            if ($elements->length !== 1) {
                throw new ResponseException('Nie można pobrać informacji o identyfikatorze świadczeniodawcy.');
            }
        }
        $response->setData($status === '1' ? $elements->item(0)->nodeValue : null, CheckCwuResponse::DATA_PROVIDER);

        return $response;        
    }
    
}
