<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Credentials;
use Gilek\Ewus\Session;

class ChangePasswordRequest implements RequestInterface
{
    /** @var Session */
    private $session;

    /** @var Credentials */
    private $credentials;

    /** @var string */
    private $newPassword;

    /**
     * @param Session     $session
     * @param Credentials $credentials
     * @param string      $newPassword
     */
    public function __construct(Session $session, Credentials $credentials, string $newPassword)
    {
        $this->session = $session;
        $this->credentials = $credentials;
        $this->newPassword = $newPassword;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
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
}
