<?php

namespace gilek\ewus\operations;

use gilek\ewus\responses\ChangePasswordResponse;

class ChangePasswordOperation extends BaseOperation
{
    /**
     *
     * @var string 
     */
    private $newPassword;
    
    /**
     * 
     * @return string
     */
    function getNewPassword() {
        return $this->newPassword;
    }

    /**
     * 
     * @param string $newPassword
     */
    function setNewPassword($newPassword) {
        $this->newPassword = $newPassword;
    }

    /**
     * 
     * @param string $newPassword
     */
    public function __construct($newPassword) {
        $this->setNewPassword($newPassword);
    }

    /**
     * 
     * @inheritdoc
     */
    public function makeRequestXml() {
        $request = $this->getSession()->getRequest();
        $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="http://xml.kamsoft.pl/ws/common" xmlns:auth="http://xml.kamsoft.pl/ws/kaas/login_types">
            <soapenv:Header>
               <com:authToken id="' . $this->getSession()->getToken() . '"/>
               <com:session id="' . $this->getSession()->getSessionId() . '"/>               
            </soapenv:Header>
            <soapenv:Body>
                <auth:changePassword>
                    <auth:credentials>
                        <auth:item>
                            <auth:name>login</auth:name>
                            <auth:value><auth:stringValue>' . $request->getLogin() . '</auth:stringValue></auth:value>
                        </auth:item>';
        foreach ((array)$request->getParams() as $key => $value) {
            $xml.= '<auth:item>
                        <auth:name>' . $key . '</auth:name>
                        <auth:value><auth:stringValue>' . $value . '</auth:stringValue></auth:value>
                    </auth:item>';
        }
        $xml .= '</auth:credentials>
                    <auth:oldPassword>' . $this->getPassword() . '</auth:oldPassword>
                    <auth:newPassword>' . $this->newPassword . '</auth:newPassword>
                    <auth:newPasswordRepeat>' . $this->newPassword . '</auth:newPasswordRepeat>
                </auth:changePassword>
            </soapenv:Body>
        </soapenv:Envelope>';        
    }

    /**
     * 
     * @inheritdoc
     */    
    public function makeResponse(\DOMDocument $dom) {
        $response = new ChangePasswordResponse();
        
        $xpath = new \DOMXpath($dom);
        $xpath->registerNamespace('lt', 'http://xml.kamsoft.pl/ws/kaas/login_types');

        $element = $xpath->query("//lt:changePasswordReturn");
        if ($element->length === 0)
            throw new ResponseException('Nie można pobrać informacji zwrotnej.');

        $response->setMessage($element->item(0)->nodeValue);
        return $response;
    }
}