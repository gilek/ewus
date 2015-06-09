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
    public function getToken() {
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
    public function setToken($token) {
        $this->token = $token;
    }
    
    /**
     * 
     * @inheritdoc
     */
    public function getLogin() {
        return $this->getOperation()->getLogin();
    }
    
    /**
     * 
     * @inheritdoc
     */
    public function getPassword() {
        return $this->getOperation()->getPassword();
    }
    
    /**
     * 
     * @inheritdoc
     */
    public function getLoginParams() {
        return $this->getOperation()->getParams();
    }    
    
    /**
     * 
     * @return string
     */
    public function getLoginMessage() {
        return $this->loginMessage;
    }
    
    /**
     * 
     * @return string
     */
    public function getLoginMessageCode() {
        return $this->loginMessageCode;
    }

    /**
     * 
     * @param string $loginMessage
     */
    public function setLoginMessage($loginMessage) {
        $this->loginMessage = $loginMessage;
    }

    /**
     * 
     * @param string $loginMessageCode
     */
    public function setLoginMessageCode($loginMessageCode) {
        $this->loginMessageCode = $loginMessageCode;
    }


}
