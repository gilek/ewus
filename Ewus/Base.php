<?php
/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0a
 * @package Ewus
 */
namespace Ewus;

require_once 'Exceptions.php';

abstract class Base {
    
    /**
     *
     * @var SoapClient 
     */
    private $_authService;    
    
    /**
     *
     * @var string 
     */
    protected $_authServiceUrl = 'https://ewus.nfz.gov.pl/ws-broker-server-ewus/services/Auth?wsdl';    
             
    /**
     *
     * @var SoapClient 
     */
    private $_brokerService;

    /**
     *
     * @var string 
     */
    private $_brokerServiceUrl = 'https://ewus.nfz.gov.pl/ws-broker-server-ewus/services/ServiceBroker?wsdl';
    
    /**
     *
     * @var DOMDocument 
     */
    protected $_lastDomReponse;    
    
    /**
     * 
     * @return \SoapClient
     * @throws ServiceException
     */
    public function getAuthService() {
        if ($this->_authService===null) {
            try {
                $this->_authService = new \SoapClient($this->_authServiceUrl);
            } catch(Exception $e) {
                throw new ServiceException('Usługa autoryzacji eWUŚ nie jest dostępna.');
            }
        }
        return $this->_authService;
    } 

    /**
     * 
     * @return string
     */
    public function getAuthServiceUrl() {
        return $this->_authServiceUrl;
    }    
    
    /**
     * 
     * @param SoapClient $authService
     */
    public function setAuthService(SoapClient $authService) {
        $this->_authService = $authService;
    }

    /**
     * 
     * @param string $authServiceUrl
     */
    public function setAuthServiceUrl($authServiceUrl) {
        $this->_authServiceUrl = $authServiceUrl;
    }    
    
    /**
     * 
     * @param array $params
     * @return \SoapClient
     * @throws ServiceException
     */
    public function getBrokerService() {
        if ($this->_brokerService===null) {
            try {
                $this->_brokerService = new \SoapClient($this->_brokerServiceUrl);
            } catch(Exception $e) {
                throw new ServiceException('Usługa brokera eWUŚ nie jest dostępna.');
            }
        }
        return $this->_brokerService;
    } 

    /**
     * 
     * @return string
     */
    public function getBrokerServiceUrl() {
        return $this->_brokerServiceUrl;
    }

    /**
     * 
     * @param string $response
     * @return boolean
     * @throws ResponseException
     * @throws Exception
     */
    protected function _parseResponse($response) {
        if (strlen($response)===0)
            throw new ResponseException('Brak odpowiedzi na żądanie.');
                
        $doc = new \DOMDocument();
        try {
            $doc->loadXML($response);  
        } catch(Exception $e) {
            throw new ResponseException('Nieprawidłowy format odpowiedzi.');
        }
        $this->_lastDomReponse = $doc;
        
        $xpath = new \DOMXpath($doc);
        $xpath->registerNamespace('env', 'http://schemas.xmlsoap.org/soap/envelope/');
        $xpath->registerNamespace('xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $xpath->registerNamespace('com', 'http://xml.kamsoft.pl/ws/common');
        
        $fault = $xpath->query('//env:Fault');
        
        if ($fault->length===1) {
            $e = new Exception();
            $com = $xpath->query('//com:*');
            if ($com->length>=1) {
                $exceptionType = $com->item(0)->getAttribute("xsi:type");
                $exceptionName = '\\Ewus\\'.mb_substr($exceptionType,  mb_strpos($exceptionType, ':')+1);
                
                $e = new $exceptionName;

                $com = $xpath->query('//com:faultstring');
                if ($com->length===1) 
                    $e->setMessage($com->item(0)->nodeValue);
            }    
            throw $e;
        }
        
        return true;
    }    
}

