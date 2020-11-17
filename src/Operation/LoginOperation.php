<?php
declare(strict_types=1);

namespace Gilek\Ewus\Operation;

use Gilek\Ewus\Response\LoginResponse;

class LoginOperation extends BaseOperation {
    /**
     *
     * @var string 
     */
    public $login;
    
    /**
     *
     * @var string 
     */
    public $password;
    
    /**
     *
     * @var array
     */
    public $params;
    
    /**
     * 
     * @return string
     */
    function getLogin() {
        return $this->login;
    }

    /**
     * 
     * @return string
     */
    function getPassword() {
        return $this->password;
    }

    /**
     * 
     * @return string
     */
    function getParams() {
        return $this->params;
    }

    /**
     * 
     * @param string $login
     */
    function setLogin($login) {
        $this->login = $login;
    }

    /**
     * 
     * @param string $password
     */
    function setPassword($password) {
        $this->password = $password;
    }

    /**
     * 
     * @param array $params
     */
    function setParams($params) {
        $this->params = $params;
    }
   
    /**
     * 
     * @param string $login
     * @param string $password
     * @param array $params
     */
    public function __construct($login, $password, $params = []) {
        $this->setLogin($login);
        $this->setPassword($password);
        $this->setParams($params);
    }

    /**
     * 
     * {@inheritDoc}
     */    
    public function makeRequestXml() {
        $xml = '<soapenv:Envelope xmlns:soapenv = "http://schemas.xmlsoap.org/soap/envelope/" xmlns:auth = "http://xml.kamsoft.pl/ws/kaas/login_types">
            <soapenv:Header/>
            <soapenv:Body>
                <auth:login>
                    <auth:credentials>
                        <auth:item>
                            <auth:name>login</auth:name>
                            <auth:value><auth:stringValue>' . $this->getLogin() . '</auth:stringValue></auth:value>
                        </auth:item>';
        foreach ($this->getParams() as $key => $value) {
                $xml.= '<auth:item>
                            <auth:name>' . $key . '</auth:name>
                            <auth:value><auth:stringValue>' . $value . '</auth:stringValue></auth:value>
                        </auth:item>';
        }
        $xml .= '</auth:credentials>
                    <auth:password>' . $this->getPassword() . '</auth:password>
                </auth:login>
            </soapenv:Body>
        </soapenv:Envelope>';
               
        return $xml;
    }

    /**
     * 
     * {@inheritDoc}
     */    
    public function makeResponse(\DOMDocument $xml) {
        $xpath = new \DOMXpath($xml);
        $xpath->registerNamespace('com', 'http://xml.kamsoft.pl/ws/common');
        $xpath->registerNamespace('lt', 'http://xml.kamsoft.pl/ws/kaas/login_types');

        $loginResponse = new LoginResponse();

        $element = $xpath->query("//com:session");
        if ($element->length === 0) {
            throw new \Exception('Nie można pobrać informacji o identifikatorze sesji.');
        }
        $loginResponse->setSessionId($element->item(0)->getAttribute('id'));

        $element = $xpath->query("//com:authToken");
        if ($element->length === 0) {
            throw new \Exception('Nie można pobrać informacji o tokenie.');
        }
        $loginResponse->setToken($element->item(0)->getAttribute('id'));

        $element = $xpath->query("//lt:loginReturn");
        if ($element->length === 0) {
            throw new \Exception('Nie można pobrać informacji zwrotnej.');
        }
        if (false === preg_match_all('/^\[([0-9]{3})\] (.*)$/', $message = $element->item(0)->nodeValue, $matches) || count($matches) != 3) {
            throw new \Exception('Nieprawidłowy format informacji zwrotnej.');
        }    
        $loginResponse->setLoginMessage($matches[2][0]);
        $loginResponse->setLoginMessageCode($matches[1][0]);
        
        return $loginResponse;                
    }
}

