<?php

namespace gilek\ewus\responses;

interface Session {
    /**
     * 
     * return string
     */
    public function getSessionId();
    
    /**
     * 
     * return string 
     */
    public function getToken();
    
    /**
     * 
     * @return string
     */
    public function getLogin();
    
    /**
     * 
     * @return string
     */
    public function getPassword();
    
    /**
     * 
     * @return array
     */
    public function getLoginParams();    
        
}
