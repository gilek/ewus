<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Shared\XmlServiceFactory;
use Gilek\Ewus\Session;

class LogoutRequestFactory
{
    use WithSessionHeader;

    private const NS_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';
    private const NS_AUTH = 'http://xml.kamsoft.pl/ws/kaas/login_types';
    private const NS_COMMON = 'http://xml.kamsoft.pl/ws/common';

    /** @var XmlServiceFactory */
    private $xmlServiceFactory;

    /**
     * @param XmlServiceFactory $xmlServiceFactory
     */
    public function __construct(XmlServiceFactory $xmlServiceFactory)
    {
        $this->xmlServiceFactory = $xmlServiceFactory;
    }

    /**
     * @param Session $session
     *
     * @return Request
     */
    public function build(Session $session): Request
    {
        return new Request('logout', $this->generateBody($session));
    }

    /**
     * @param Session $session
     *
     * @return string
     */
    private function generateBody(Session $session)
    {
        $xmlService = $this->xmlServiceFactory->create([
            self::NS_SOAP => 'soapenv',
            self::NS_AUTH => 'auth',
            self::NS_COMMON => 'com',
        ]);

        $soapNs = '{' . self::NS_SOAP . '}';
        $authNs = '{' . self::NS_AUTH . '}';

        return $xmlService->write($soapNs . 'Envelope', [
            $soapNs . 'Header' => $this->generateSessionHeaders($session, self::NS_COMMON),
            $soapNs . 'Body' => [
                $authNs .  'logout' => ''
            ]
        ]);
    }
}
