<?php

namespace gilek\ewus\exceptions;

class Exception extends \Exception {

    /**
     * 
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

}
