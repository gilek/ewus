<?php
declare(strict_types=1);

namespace Gilek\Ewus\Service;

use Gilek\Ewus\exception\Exception;

class ServiceManager
{
    /**
     * TODO this namespace does nto make any sense
     * TODO omg static
     *
     * @param string $name
     *
     * @return ServiceInterface
     *
     * @throws Exception
     */
    public static function get(string $name): ServiceInterface {
        switch($name) {
            case 'auth': 
                return new AuthServiceInterface();
            
            case 'broker':
                return new BrokerServiceInterface();
            
            default:
                // TODO ponglish?
                throw new Exception('Nieprawidłowa nazwa usługi.');
        }
    }
}
