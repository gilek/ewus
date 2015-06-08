<?php

namespace gilek\ewus\services;

use gilek\ewus\exception\Exception;

class ServiceManager {    
    /**
     * 
     * @param string $name
     * @return \gilek\ewus\services\Service
     * @throws Exception
     */
    public static function get($name) {
        switch($name) {
            case 'auth': 
                return new AuthService();
                break;
            
            case 'broker':
                return new BrokerService();
                break;
            
            default:
                throw new Exception('Nieprawidłowa nazwa usługi.');
                break;
        }
    }
    
    
}