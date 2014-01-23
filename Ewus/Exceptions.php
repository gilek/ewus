<?php
/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0a
 * @package Ewus
 */
namespace Ewus;

class Exception extends \Exception {
    /**
     * 
     * @param string $message
     */
    public function setMessage($message) { 
        $this->message = $message; 
    }
}
class AuthorizationException extends Exception {}
class AuthenticationException extends Exception {}
class ServiceException extends Exception {}
class AuthTokenException extends Exception {}
class ServerException extends Exception {}
class InputException extends Exception {}
class SessionException extends Exception {}
class ResponseException extends Exception {}


