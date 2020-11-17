<?php
declare(strict_types=1);

namespace Gilek\Ewus\Operation;

use Gilek\Ewus\Response\ChangePasswordResponse;

class ChangePasswordOperation extends BaseOperation
{
    /** @var string */
    private $newPassword;

    /**
     * @param string $newPassword
     */
    public function __construct(string $newPassword)
    {
        $this->setNewPassword($newPassword);
    }

    /**
     * @return string
     */
    public function getNewPassword(): string
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     */
    public function setNewPassword(string $newPassword): void
    {
        $this->newPassword = $newPassword;
    }

    /**
     * {@inheritDoc}
     */
    public function makeRequestXml(): string {
        // TODO omg

        $session = $this->getSession();
        $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="http://xml.kamsoft.pl/ws/common" xmlns:auth="http://xml.kamsoft.pl/ws/kaas/login_types">
            <soapenv:Header>
               <com:authToken id="' . $session->getToken() . '"/>
               <com:session id="' . $session->getSessionId() . '"/>               
            </soapenv:Header>
            <soapenv:Body>
                <auth:changePassword>
                    <auth:credentials>
                        <auth:item>
                            <auth:name>login</auth:name>
                            <auth:value><auth:stringValue>' . $session->getLogin() . '</auth:stringValue></auth:value>
                        </auth:item>';
        foreach ((array)$session->getLoginParams() as $key => $value) {
            $xml.= '<auth:item>
                        <auth:name>' . $key . '</auth:name>
                        <auth:value><auth:stringValue>' . $value . '</auth:stringValue></auth:value>
                    </auth:item>';
        }
        $xml .= '</auth:credentials>
                    <auth:oldPassword>' . $session->getPassword() . '</auth:oldPassword>
                    <auth:newPassword>' . $this->getNewPassword() . '</auth:newPassword>
                    <auth:newPasswordRepeat>' . $this->getNewPassword() . '</auth:newPasswordRepeat>
                </auth:changePassword>
            </soapenv:Body>
        </soapenv:Envelope>';     
		
        return $xml;
		
    }

    /**
     * 
     * {@inheritDoc}
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
