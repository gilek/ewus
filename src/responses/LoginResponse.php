<?php

namespace gilek\ewus\responses;

class LoginResponse extends Response implements Session {
    
    /**
     *
     * @var string 
     */
    private $sessionId;
    
    /**
     *
     * @var string 
     */
    private $token;
    
    /**
     *
     * @var string 
     */
    private $loginMessage;
    
    /**
     *
     * @var string 
     */
    private $loginMessageCode;
    
    /**
     * 
     * @inheritdoc
     */
    function getSessionId() {
        return $this->sessionId;
    }

    /**
     * 
     * @inheritdoc
     */
    function getToken() {
        return $this->token;
    }

    /**
     * 
     * @inheritdoc
     */
    function setSessionId($sessionId) {
        $this->sessionId = $sessionId;
    }

    /**
     * 
     * @inheritdoc
     */
    function setToken($token) {
        $this->token = $token;
    }
    
    /**
     * 
     * @return string
     */
    function getLoginMessage() {
        return $this->loginMessage;
    }

    /**
     * 
     * @return string
     */
    function getLoginMessageCode() {
        return $this->loginMessageCode;
    }

    /**
     * 
     * @param string $loginMessage
     */
    function setLoginMessage($loginMessage) {
        $this->loginMessage = $loginMessage;
    }

    /**
     * 
     * @param string $loginMessageCode
     */
    function setLoginMessageCode($loginMessageCode) {
        $this->loginMessageCode = $loginMessageCode;
    }


}
