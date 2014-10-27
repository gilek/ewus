<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.1a
 * @package ewus
 */

namespace gilek\ewus\exception;

class Exception extends \Exception
{

    /**
     * 
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

}
