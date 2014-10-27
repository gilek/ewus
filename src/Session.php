<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.1a
 * @package ewus
 */

namespace gilek\ewus;

use gilek\ewus\exception\Exception;
use gilek\ewus\exception\ResponseException;
use gilek\ewus\exception\InputException;
use gilek\ewus\Status;
use gilek\ewus\CWUResponse;

class Session extends Base
{

    /**
     *
     * @var string 
     */
    private $_login;

    /**
     *
     * @var string 
     */
    private $_password;

    /**
     *
     * @var string 
     */
    private $_sessionId;

    /**
     *
     * @var string 
     */
    private $_token;

    /**
     *
     * @var string 
     */
    private $_loginMessage;

    /**
     *
     * @var string 
     */
    private $_loginMessageCode;

    /**
     *
     * @var array 
     */
    private $_loginParams;

    /**
     * 
     * @return string
     */
    public function getLogin()
    {
        return $this->_login;
    }

    /**
     * 
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * 
     * @return string
     */
    public function getSessionId()
    {
        return $this->_sessionId;
    }

    /**
     * 
     * @return string
     */
    public function getToken()
    {
        return $this->_token;
    }

    /**
     * 
     * @return string
     */
    public function getLoginMessage()
    {
        return $this->_loginMessage;
    }

    /**
     * 
     * @return string
     */
    public function getLoginMessageCode()
    {
        return $this->_loginMessageCode;
    }

    /**
     * 
     * @return array
     */
    public function getLoginParams()
    {
        return $this->_loginParams;
    }

    /**
     * 
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->_login = $login;
    }

    /**
     * 
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * 
     * @param string $sessionId
     */
    public function setSessionId($sessionId)
    {
        $this->_sessionId = $sessionId;
    }

    /**
     * 
     * @param string $token
     */
    public function setToken($token)
    {
        $this->_token = $token;
    }

    /**
     * 
     * @param string $loginMessage
     */
    public function setLoginMessage($loginMessage)
    {
        $this->_loginMessage = $loginMessage;
    }

    /**
     * 
     * @param string $loginMessageCode
     */
    public function setLoginMessageCode($loginMessageCode)
    {
        $this->_loginMessageCode = $loginMessageCode;
    }

    /**
     * 
     * @param array $loginParams
     */
    public function setLoginParams($loginParams)
    {
        $this->_loginParams = $loginParams;
    }

    /**
     * 
     * @return boolean
     */
    public function logout()
    {
        $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="http://xml.kamsoft.pl/ws/common" xmlns:auth="http://xml.kamsoft.pl/ws/kaas/login_types">
            <soapenv:Header>
                <com:authToken id="' . $this->getToken() . '"/>
                <com:session id="' . $this->getSessionId() . '"/>
            </soapenv:Header>
            <soapenv:Body>
                <auth:logout></auth:logout>
            </soapenv:Body>
        </soapenv:Envelope>';

        $service = $this->getAuthService();
        $response = $service->__doRequest($xml, $this->_authServiceUrl, 'logout', SOAP_1_1);

        $this->_parseResponse($response);

        return true;
    }

    /**
     * 
     * @param string $newPassword
     * @return string
     * @throws ResponseException
     */
    public function changePassword($newPassword)
    {
        $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="http://xml.kamsoft.pl/ws/common" xmlns:auth="http://xml.kamsoft.pl/ws/kaas/login_types">
            <soapenv:Header>
               <com:authToken id="' . $this->getToken() . '"/>
               <com:session id="' . $this->getSessionId() . '"/>               
            </soapenv:Header>
            <soapenv:Body>
                <auth:changePassword>
                    <auth:credentials>
                        <auth:item>
                            <auth:name>login</auth:name>
                            <auth:value><auth:stringValue>' . $this->getLogin() . '</auth:stringValue></auth:value>
                        </auth:item>';
        foreach ((array) $this->getLoginParams() as $key => $value) {
            $xml.= '<auth:item>
                        <auth:name>' . $key . '</auth:name>
                        <auth:value><auth:stringValue>' . $value . '</auth:stringValue></auth:value>
                    </auth:item>';
        }
        $xml .= '</auth:credentials>
                    <auth:oldPassword>' . $this->getPassword() . '</auth:oldPassword>
                    <auth:newPassword>' . $newPassword . '</auth:newPassword>
                    <auth:newPasswordRepeat>' . $newPassword . '</auth:newPasswordRepeat>
                </auth:changePassword>
            </soapenv:Body>
        </soapenv:Envelope>';


        $service = $this->getAuthService();
        $response = $service->__doRequest($xml, $this->getAuthServiceUrl(), 'executeService', SOAP_1_1);

        $this->_parseResponse($response);

        $xpath = new \DOMXpath($this->_lastDomReponse);
        $xpath->registerNamespace('lt', 'http://xml.kamsoft.pl/ws/kaas/login_types');

        $element = $xpath->query("//lt:changePasswordReturn");
        if ($element->length === 0)
            throw new ResponseException('Nie można pobrać informacji zwrotnej.');

        return $element->item(0)->nodeValue;
    }

    /**
     * 
     * @param string $pesel
     * @param integer $flags
     * @return CWUResponse
     * @throws InputException
     * @throws Exception
     */
    public function checkCWU($pesel, $flags = 63)
    {
        $xml = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:com="http://xml.kamsoft.pl/ws/common" xmlns:brok="http://xml.kamsoft.pl/ws/broker">
           <soapenv:Header>
              <com:session id="' . $this->getSessionId() . '" xmlns:ns1="http://xml.kamsoft.pl/ws/common"/>
              <com:authToken id="' . $this->getToken() . '" xmlns:ns1="http://xml.kamsoft.pl/ws/common"/>
           </soapenv:Header>
           <soapenv:Body>
              <brok:executeService>
                 <com:location>
                    <com:namespace>nfz.gov.pl/ws/broker/cwu</com:namespace>
                    <com:localname>checkCWU</com:localname>
                    <com:version>3.0</com:version>
                 </com:location>
                 <brok:payload>
                    <brok:textload>
                       <ewus:status_cwu_pyt xmlns:ewus="https://ewus.nfz.gov.pl/ws/broker/ewus/status_cwu/v3">
                          <ewus:numer_pesel>' . $pesel . '</ewus:numer_pesel>
                          <ewus:system_swiad nazwa="EwusClient by gilek" wersja="1.1"/>
                       </ewus:status_cwu_pyt>
                    </brok:textload>
                 </brok:payload>
              </brok:executeService>
           </soapenv:Body>
        </soapenv:Envelope>';


        $service = $this->getBrokerService();
        $response = $service->__doRequest($xml, $this->getBrokerServiceUrl(), 'executeService', SOAP_1_1);

        try {
            $this->_parseResponse($response);
        } catch (InputException $e) {
            $e->setMessage('Nieprawidłowy numer PESEL.');
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }

        return $this->getChckCWUData($this->_lastDomReponse, $flags);
    }

    /**
     * 
     * @param \DOMDocument $doc
     * @param integer $flags
     * @return CWUResponse
     * @throws ResponseException
     */
    public function getChckCWUData(\DOMDocument $doc, $flags)
    {
        $xpath = new \DOMXpath($doc);
        $xpath->registerNamespace('odp', 'https://ewus.nfz.gov.pl/ws/broker/ewus/status_cwu/v3');

        $response = new CWUResponse();

        if ($flags & CWUResponse::FLAG_RESPONSE) {
            $response->setData(CWUResponse::FLAG_RESPONSE, $doc->saveXML());
        }

        if ($flags & CWUResponse::FLAG_OPERATION_ID) {
            $elements = $xpath->query("//odp:status_cwu_odp");
            if ($elements->length !== 1) {
                throw new ResponseException('Nie można pobrać informacji o id operacji.');
            }

            $response->setData(CWUResponse::FLAG_OPERATION_ID, $elements->item(0)->getAttribute('id_operacji'));
        }

        $elements = $xpath->query("//odp:status_cwu");
        if ($elements->length !== 1) {
            throw new ResponseException('Nie można pobrać informacji o statusie pacjenta.');
        }

        $status = $elements->item(0)->nodeValue;

        if ($flags & CWUResponse::FLAG_STATUS) {
            if ($status === '1') {
                $elements = $xpath->query("//odp:status_ubezp");
                if ($elements->length !== 1) {
                    throw new ResponseException('Nie można pobrać informacji o uprawnieniu do świadczeń.');
                }
            }
            $response->setData(CWUResponse::FLAG_STATUS, $status === '1' ? $elements->item(0)->nodeValue : Status::STATUS_NOT_EXIST);
        }

        if ($flags & CWUResponse::FLAG_PATIENT_NAME) {
            if ($status === '1') {
                $elements = $xpath->query("//odp:imie");
                if ($elements->length !== 1) {
                    throw new ResponseException('Nie można pobrać informacji o imieniu pacjenta.');
                }
            }
            $response->setData(CWUResponse::FLAG_PATIENT_NAME, $status === '1' ? $elements->item(0)->nodeValue : null);
        }

        if ($flags & CWUResponse::FLAG_PATIENT_SURNAME) {
            if ($status === '1') {
                $elements = $xpath->query("//odp:nazwisko");
                if ($elements->length !== 1) {
                    throw new ResponseException('Nie można pobrać informacji o nazwisko upacjenta.');
                }
            }
            $response->setData(CWUResponse::FLAG_PATIENT_SURNAME, $status === '1' ? $elements->item(0)->nodeValue : null);
        }

        if ($flags & CWUResponse::FLAG_PROVIDER) {
            if ($status === '1') {
                $elements = $xpath->query("//odp:id_ow");
                if ($elements->length !== 1) {
                    throw new ResponseException('Nie można pobrać informacji o identyfikatorze świadczeniodawcy.');
                }
            }
            $response->setData(CWUResponse::FLAG_PROVIDER, $status === '1' ? $elements->item(0)->nodeValue : null);
        }

        return $response;
    }

}
