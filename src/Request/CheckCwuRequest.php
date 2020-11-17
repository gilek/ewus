<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Session;

class CheckCwuRequest implements RequestInterface
{
    /** @var string */
    private $pesel;

    /** @var Session */
    private $session;

    /**
     * @param string  $pesel
     * @param Session $session
     */
    public function __construct(Session $session, string $pesel)
    {
        $this->session = $session;
        $this->pesel = $pesel;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="http://xml.kamsoft.pl/ws/common" xmlns:brok="http://xml.kamsoft.pl/ws/broker">
           <soapenv:Header>
              <com:session id="' . $this->session->getSessionId() . '" xmlns:ns1="http://xml.kamsoft.pl/ws/common"/>
              <com:authToken id="' . $this->session->getToken() . '" xmlns:ns1="http://xml.kamsoft.pl/ws/common"/>
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
                          <ewus:numer_pesel>' . $this->pesel . '</ewus:numer_pesel>
                          <ewus:system_swiad nazwa="EwusClient by gilek" wersja="1.1"/>
                       </ewus:status_cwu_pyt>
                    </brok:textload>
                 </brok:payload>
              </brok:executeService>
           </soapenv:Body>
        </soapenv:Envelope>';
    }
}
