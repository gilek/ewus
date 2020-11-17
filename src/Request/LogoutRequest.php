<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Session;

/** Class LogoutRequest */
class LogoutRequest implements RequestInterface
{
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
     * @return string
     */
    public function getBody(): string
    {
        // TODO not in such way
        return '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="http://xml.kamsoft.pl/ws/common" xmlns:auth="http://xml.kamsoft.pl/ws/kaas/login_types">
            <soapenv:Header>
                <com:authToken id="' . $this->session->getToken() . '"/>
                <com:session id="' . $this->session->getSessionId() . '"/>
            </soapenv:Header>
            <soapenv:Body>
                <auth:logout></auth:logout>
            </soapenv:Body>
        </soapenv:Envelope>';
    }
}
