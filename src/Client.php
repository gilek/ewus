<?php

namespace gilek\ewus;

use gilek\ewus\drivers\Driver;
use gilek\ewus\responses\Session;
use gilek\ewus\services\ServiceManager;
use gilek\ewus\operations\LoginOperation;
use gilek\ewus\operations\Operation;
use gilek\ewus\services\Service;
use gilek\ewus\operations\CheckPeselOperation;
use gilek\ewus\operations\LogoutOperation;

class Client {
    /**
     *
     * @var Driver
     */
    private $driver;
    
    /**
     *
     * @var Session 
     */
    private $session;
        
    /**
     * 
     * @param Driver $driver
     */
    public function __construct(Driver $driver) {
        $this->driver = $driver;
    }
    
    /**
     * 
     * @return Session
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * 
     * @param Session $session
     */
    public function setSession(Session $session) {
        $this->session = $session;
    }

    /**
     * 
     * @param string $login
     * @param string $password
     * @param array $params
     * @return Response
     */
    public function login($login, $password, $params) {
        $response = $this->doOperation(new LoginOperation($login, $password, $params), ServiceManager::get('auth'));
        if ($response instanceof LoginResponse) {
            $this->setSession($response);
        }
        return $response;
        
    }
        
    /**
     * 
     * @param string $pesel
     * @return Response     
     */    
    public function checkPesel($pesel) {
        return $this->doOperation(new CheckPeselOperation($pesel), ServiceManager::get('broker'));        
    }
    
    /**
     * 
     * @return Response
     */
    public function logout() {
        return $this->doOperation(new LogoutOperation(), ServiceManager::get('auth'));            
    }
    
    /**
     * 
     * @param string $newPassword
     * @return Response
     */
    public function changePassword($newPassword) {
        return $this->doOperation(new ChangePasswordOperation($newPassword), ServiceManager::get('auth'));          
    }
    
    /**
     * 
     * @param Operation $operation
     * @param Service $service
     * @return type
     */
    public function doOperation(Operation $operation, Service $service) {
        if ($this->getSession() !== null) {
            $operation->setSession($this->getSession());
        }
        $this->driver->setService($service);
        $operation->setDriver($this->driver);
        return $operation->run();
    }
}