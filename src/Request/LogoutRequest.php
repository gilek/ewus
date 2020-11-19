<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Factory\XmlServiceFactory;
use Gilek\Ewus\Session;

/** Class LogoutRequest */
class LogoutRequest implements RequestInterface
{
    private const NS_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';
    private const NS_AUTH = 'http://xml.kamsoft.pl/ws/kaas/login_types';
    private const NS_COMMON = 'http://xml.kamsoft.pl/ws/common';

    /** @var Session */
    private $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodName(): string
    {
        return 'logout';
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        $xmlService = (new XmlServiceFactory())->create([
            self::NS_SOAP => 'soapenv',
            self::NS_AUTH => 'auth',
            self::NS_COMMON => 'com',
        ]);

        $soapNs = '{' . self::NS_SOAP . '}';
        $authNs = '{' . self::NS_AUTH . '}';
        $comNs = '{' . self::NS_COMMON . '}';

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
                $authNs .  'logout' => ''
            ]
        ]);
    }
}
