<?php

namespace Gilek\Ewus;

use Gilek\Ewus\Driver\DriverInterface;
use Gilek\Ewus\Response\ChangePasswordResponse;
use Gilek\Ewus\Response\Response;
use Gilek\Ewus\Response\SessionInterface;
use Gilek\Ewus\Service\ServiceManager;
use Gilek\Ewus\Service\ServiceInterface;
use Gilek\Ewus\Operation\OperationInterface;
use Gilek\Ewus\Operation\LoginOperation;
use Gilek\Ewus\Operation\CheckPeselOperation;
use Gilek\Ewus\Operation\ChangePasswordOperation;
use Gilek\Ewus\Operation\LogoutOperation;

class Client
{
    /** @var DriverInterface */
    private $driver;
    
    /** @var SessionInterface */
    private $session;

    /**
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }
    
    /**
     * @return SessionInterface
     */
    public function getSession(): ServiceInterface
    {
        return $this->session;
    }

    /**
     * TODO PUBLKIC?
     *
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session):void {
        $this->session = $session;
    }

    /**
     * 
     * @param string $login
     * @param string $password
     * @param array $params
     *
     * @return Response
     */
    public function login(string $login, string $password, array $params): Response
    {
        $response = $this->doOperation(new LoginOperation($login, $password, $params), ServiceManager::get('auth'));
        if ($response instanceof SessionInterface) {
            $this->setSession($response);
        }

        return $response;
        
    }
        
    /**
     * 
     * @param string $pesel
     * @return Response     
     */    
    public function checkPesel(string $pesel): Response
    {
        return $this->doOperation(new CheckPeselOperation($pesel), ServiceManager::get('broker'));        
    }
    
    /**
     * @return LogoutOperation
     */
    public function logout(): LoginOperation
    {
        return $this->doOperation(new LogoutOperation(), ServiceManager::get('auth'));            
    }
    
    /**
     * 
     * @param string $newPassword
     *
     * @return ChangePasswordResponse
     */
    public function changePassword(string $newPassword): ChangePasswordResponse
    {
        return $this->doOperation(new ChangePasswordOperation($newPassword), ServiceManager::get('auth'));          
    }
    
    /**
     * @param OperationInterface $operation
     * @param ServiceInterface   $service
     *
     * @return Response
     */
    public function doOperation(OperationInterface $operation, ServiceInterface $service): Response
    {
        if ($this->getSession() !== null) {
            $operation->setSession($this->getSession());
        }
        $this->driver->setService($service);
        $operation->setDriver($this->driver);

        return $operation->run();
    }
}
