<?php
declare(strict_types=1);

namespace Gilek\Ewus\Request;

use DateTimeImmutable;
use Gilek\Ewus\Client;
use Gilek\Ewus\Ns;
use Gilek\Ewus\Request\Session;

class CheckCwuRequestFactory
{
    use WithSessionHeader;

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
     * @param string $pesel
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
     * @param string $pesel
     *
     * @return string
     */
    private function generateBody(Session $session, string $pesel): string
    {
        $xmlService = $this->xmlWriterFactory->create([
            Ns::SOAP => 'soapenv',
            Ns::COMMON => 'com',
            Ns::BROKER => 'brok',
            Ns::EWUS => 'ewus',
        ]);

        $soapNs = '{' . Ns::SOAP . '}';
        $comNs = '{' . Ns::COMMON . '}';
        $brokNs = '{' . Ns::BROKER . '}';
        $ewusNs = '{' . Ns::EWUS . '}';

        return $xmlService->write($soapNs . 'Envelope', [
            $soapNs . 'Header' => $this->generateSessionHeaders($session, Ns::COMMON),
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
