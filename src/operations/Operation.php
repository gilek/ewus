<?php

namespace gilek\ewus\operations;

use gilek\ewus\responses\Session; 
use gilek\ewus\drivers\Driver;

interface Operation {
    
    /**
     * @return \gilek\ewsu\responses\Response
     */
    public function run();
    
    /**
     * 
     * @param Driver $driver
     */
    public function setDriver(Driver $driver);
    
    /**
     * @return Driver
     */
    public function getDriver();
    
    /**
     * 
     * @param Session $session
     */
    public function setSession(Session $session);
    
    /**
     * 
     * @return Session
     */
    public function getSession();
}
