<?php

namespace gilek\ewus\responses\Session;

interface Session {
    /**
     * 
     * return string
     */
    public function getSessionId();
    
    /**
     * 
     * @param string $sessionId
     */
    public function setSessionId($sessionId);
    
    /**
     * 
     * return string 
     */
    public function getToken();
    /**
     * 
     * @param string $token
     */
    public function setToken($token);
}
