<?php
/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0a
 * @package Ewus
 */
namespace Ewus;

require_once 'Base.php';
require_once 'Session.php';
require_once 'Exceptions.php';

class Client extends Base {
    
/**
 * 
 * @param string $user
 * @param string $password
 * @param string $params
 * @return Session
 * @throws SessionException
 */
    public function login($user,$password,$params=array()) {
        $xml = '<soapenv:Envelope xmlns:soapenv = "http://schemas.xmlsoap.org/soap/envelope/" xmlns:auth = "http://xml.kamsoft.pl/ws/kaas/login_types">
            <soapenv:Header/>
            <soapenv:Body>
                <auth:login>
                    <auth:credentials>
                        <auth:item>
                            <auth:name>login</auth:name>
                            <auth:value><auth:stringValue>'.$user.'</auth:stringValue></auth:value>
                        </auth:item>';
        foreach($params as $key=>$value) {
            $xml.=      '<auth:item>
                            <auth:name>'.$key.'</auth:name>
                            <auth:value><auth:stringValue>'.$value.'</auth:stringValue></auth:value>
                        </auth:item>';
        }
        $xml .=     '</auth:credentials>
                    <auth:password>'.$password.'</auth:password>
                </auth:login>
            </soapenv:Body>
        </soapenv:Envelope>';

        $service = $this->getAuthService();    
        $response = $service->__doRequest($xml, $this->_authServiceUrl, 'executeService', SOAP_1_1);             
        $this->_parseResponse($response);        
        
        $xpath = new \DOMXpath($this->_lastDomReponse);
        $xpath->registerNamespace('com','http://xml.kamsoft.pl/ws/common');
        $xpath->registerNamespace('lt','http://xml.kamsoft.pl/ws/kaas/login_types');
        
        $session = new Session();

        $element = $xpath->query("//com:session");
        if ($element->length === 0)
            throw new SessionException('Nie można pobrać informacji o identifikatorze sesji.');
        $session->setSessionId($element->item(0)->getAttribute('id'));

        $element = $xpath->query("//com:authToken");
        if ($element->length === 0)
            throw new SessionException('Nie można pobrać informacji o tokenie.');            
        $session->setToken($element->item(0)->getAttribute('id'));
         
        $element = $xpath->query("//lt:loginReturn");
        if ($element->length === 0)
            throw new SessionException('Nie można pobrać informacji zwrotnej.');  
        
        if (false === preg_match_all('/^\[([0-9]{3})\] (.*)$/',$message = $element->item(0)->nodeValue,$matches) || count($matches)!=3) {
            throw new SessionException('Nieprawidłowy format informacji zwrotnej.'); 
        }  
        $session->setLoginMessage($matches[2][0]);
        $session->setLoginMessageCode($matches[1][0]);        
        $session->setLoginParams($params); 
        
        return $session;
    }
}