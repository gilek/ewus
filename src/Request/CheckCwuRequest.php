<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use DateTimeImmutable;
use Gilek\Ewus\Client;
use Gilek\Ewus\Factory\XmlServiceFactory;
use Gilek\Ewus\Session;
use Sabre\Xml\Service;

class CheckCwuRequest implements RequestInterface
{
    private const NS_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';
    private const NS_COMMON = 'http://xml.kamsoft.pl/ws/common';
    private const NS_BROKER = 'http://xml.kamsoft.pl/ws/broker';
    private const NS_EWUS = 'https://ewus.nfz.gov.pl/ws/broker/ewus/status_cwu/v5';

    public const NAME = 'checkCwu';

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
     * {@inheritDoc}
     */
    public function getMethodName(): string
    {
        return self::NAME;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        $xmlService = (new XmlServiceFactory())->create([
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
            $soapNs . 'Header' => [
                [
                    'name' =>  $comNs . 'session',
                    'attributes' => [
                        'id' => $this->session->getSessionId()
                    ]
                ],
                [
                    'name' =>  $comNs . 'authToken',
                    'attributes' => [
                        'id' => $this->session->getSessionId()
                    ]
                ],
            ],
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
                               $ewusNs . 'numer_pesel' => $this->pesel,
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
