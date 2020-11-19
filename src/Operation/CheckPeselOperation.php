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
