<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Test\Functional\Response;

use Gilek\Ewus\Response\CheckCwuResponseFactory;
use Gilek\Ewus\Response\XmlReaderFactory;
use PHPUnit\Framework\TestCase;

final class CheckCwuResponseFactoryTest extends TestCase
{
    /** @var CheckCwuResponseFactory */
    private $sut;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->sut = new CheckCwuResponseFactory(new XmlReaderFactory());
    }

    /**
     * @test
     */
    public function is_should_create_correct_response(): void
    {
        $r = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Body>
      <ns3:executeServiceReturn xsi:type="ns3:ServiceResponse" xmlns:ns3="http://xml.kamsoft.pl/ws/broker" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
         <location xmlns="http://xml.kamsoft.pl/ws/common">
            <namespace>nfz.gov.pl/ws/broker/cwu</namespace>
            <localname>checkCWU</localname>
            <version>5.0</version>
         </location>
         <ns3:date>2020-11-20T13:44:59.411+01:00</ns3:date>
         <ns3:payload>
            <ns3:textload>
               <ns2:status_cwu_odp data_czas_operacji="2020-11-20T13:44:59.406+01:00" id_operacji="L1712M01200000001" xmlns:ns2="https://ewus.nfz.gov.pl/ws/broker/ewus/status_cwu/v5">
                  <ns2:status_cwu>1</ns2:status_cwu>
                  <ns2:numer_pesel>00072751918</ns2:numer_pesel>
                  <ns2:system_nfz nazwa="eWUS" wersja="test"/>
                  <ns2:swiad>
                     <ns2:id_swiad>TEST3</ns2:id_swiad>
                     <ns2:id_ow>01</ns2:id_ow>
                     <ns2:id_operatora>3</ns2:id_operatora>
                  </ns2:swiad>
                  <ns2:pacjent>
                     <ns2:data_waznosci_potwierdzenia>2020-11-20+01:00</ns2:data_waznosci_potwierdzenia>
                     <ns2:status_ubezp>1</ns2:status_ubezp>
                     <ns2:imie>ImięTAK</ns2:imie>
                     <ns2:nazwisko>NazwiskoTAK</ns2:nazwisko>
                     <ns2:informacje_dodatkowe>
                        <ns2:informacja kod="IZOLACJA DOMOWA" poziom="O" wartosc="Pacjent podlega izolacji domowej do dnia 04-12-2020"/>
                     </ns2:informacje_dodatkowe>
                  </ns2:pacjent>
                  <Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
                     <SignedInfo>
                        <CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315"/>
                        <SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1"/>
                        <Reference URI="">
                           <Transforms>
                              <Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/>
                           </Transforms>
                           <DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1"/>
                           <DigestValue>GMwKo/rvtGBo17Qyfr9Hj6vn0Pc=</DigestValue>
                        </Reference>
                     </SignedInfo>
                     <SignatureValue>WDtvZWEP8YPhUIjuiMxsePNdK25CJkhK60M5K3lizVkmP59mDikKjegax41oqDdPmMsZWoXEx7ZN
XVBDRAWuX3cLRASubX6IGQ9IOWI04o1WffIOqm1HDrbTAAAnytAUuFGSuKZZqzJskiVn3lKf+tk5
EJ/A/ZWRzySw+W5xd04=</SignatureValue>
                  </Signature>
               </ns2:status_cwu_odp>
            </ns3:textload>
         </ns3:payload>
      </ns3:executeServiceReturn>
   </soapenv:Body>
</soapenv:Envelope>';

        dump($this->sut->build($r));
    }
}
