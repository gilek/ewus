<?php
declare(strict_types=1);

namespace Gilek\Ewus\Operation;

use Gilek\Ewus\Response\LogoutResponse;

class LogoutOperation extends BaseOperation
{
    
    /**
     * {@inheritDoc}
     */
    public function makeRequestXml()
    {
        // TODO omg

        return '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="http://xml.kamsoft.pl/ws/common" xmlns:auth="http://xml.kamsoft.pl/ws/kaas/login_types">
            <soapenv:Header>
                <com:authToken id="' . $this->getSession()->getToken() . '"/>
                <com:session id="' . $this->getSession()->getSessionId() . '"/>
            </soapenv:Header>
            <soapenv:Body>
                <auth:logout></auth:logout>
            </soapenv:Body>
        </soapenv:Envelope>';
    }

    /**
     * {@inheritDoc}
     */    
    public function makeResponse(\DOMDocument $xml)
    {
        return new LogoutResponse();  
    }
}

