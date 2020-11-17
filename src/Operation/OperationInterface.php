<?php
declare(strict_types=1);

namespace Gilek\Ewus\Operation;

use Gilek\Ewus\Response\Response;
use Gilek\Ewus\Response\SessionInterface;
use Gilek\Ewus\Driver\DriverInterface;

interface OperationInterface {
    
    /**
     * @return Response
     */
    public function run();
    
    /**
     * 
     * @param DriverInterface $driver
     */
    public function setDriver(DriverInterface $driver);
    
    /**
     * @return DriverInterface
     */
    public function getDriver();
    
    /**
     * 
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session);
    
    /**
     * 
     * @return SessionInterface
     */
    public function getSession();
}
