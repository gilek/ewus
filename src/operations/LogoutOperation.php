<?php

namespace gilek\ewus\operations;

use gilek\ewus\responses\LogoutResponse;

class LogoutOperation extends BaseOperation {
    
    /**
     * 
     * @inheritdoc
     */
    public function makeRequestXml() {
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
     * 
     * @inheritdoc
     */    
    public function makeResponse(\DOMDocument $xml) {
        return new LogoutResponse();  
    }
}

