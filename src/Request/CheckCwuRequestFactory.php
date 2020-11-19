<?php
declare(strict_types=1);

namespace Gilek\Ewus\Request;

use DateTimeImmutable;
use Gilek\Ewus\Client;
use Gilek\Ewus\Session;

class CheckCwuRequestFactory
{
    use WithSessionHeader;

    private const NS_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';
    private const NS_COMMON = 'http://xml.kamsoft.pl/ws/common';
    private const NS_BROKER = 'http://xml.kamsoft.pl/ws/broker';
    private const NS_EWUS = 'https://ewus.nfz.gov.pl/ws/broker/ewus/status_cwu/v5';

    /** @var XmlWriterFactory */
    private $xmlWriterFactory;

    /**
     * @param XmlWriterFactory $xmlWriterFactory
     */
    public function __construct(XmlWriterFactory $xmlWriterFactory)
    {
        $this->xmlWriterFactory = $xmlWriterFactory;
    }

    /**
     * @param string  $pesel
     * @param Session $session
     *
     * @return Request
     */
    public function build(Session $session, string $pesel): Request
    {
        return new Request(Request::METHOD_CHECK_CWU, $this->generateBody($session, $pesel));
    }

    /**
     * @param Session $session
     * @param string  $pesel
     *
     * @return string
     */
    private function generateBody(Session $session, string $pesel): string
    {
        $xmlService = $this->xmlWriterFactory->create([
            self::NS_SOAP => 'soapenv',
            self::NS_COMMON => 'com',
            self::NS_BROKER => 'brok',
            self::NS_EWUS => 'ewus',
        ]);

        $soapNs = '{' . self::NS_SOAP . '}';
        $comNs = '{' . self::NS_COMMON . '}';
        $brokNs = '{' . self::NS_BROKER . '}';
        $ewusNs = '{' . self::NS_EWUS . '}';

        return $xmlService->write($soapNs . 'Envelope', [
            $soapNs . 'Header' => $this->generateSessionHeaders($session, self::NS_COMMON),
            $soapNs . 'Body' => [
                $brokNs .  'executeService' => [
                    $comNs . 'location' => [
                        $comNs . 'namespace' => 'nfz.gov.pl/ws/broker/cwu',
                        $comNs . 'localname' => 'checkCWU',
                        $comNs . 'version' => '5.0'
                    ],
                    $brokNs . 'date' => (new DateTimeImmutable())->format('c'),
                    $brokNs . 'payload' => [
                        $brokNs . 'textload' => [
                            $ewusNs . 'status_cwu_pyt' => [
                                $ewusNs . 'numer_pesel' => $pesel,
                                [
                                    'name' => 'system_swiad',
                                    'attributes' => [
                                        'nazwa' => 'gilek/ewus',
                                        'wersja' => Client::VERSION
                                    ],
                                ],
                            ]
                        ]
                    ]
                ]
            ]
        ]);
    }
}
