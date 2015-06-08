<?php

namespace gilek\ewus\responses;

class ChangePasswordResponse extends Response
{
    /**
     *
     * @var string 
     */
    private $message;
    
    /**
     * 
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * 
     * @param string $message
     */
    public function setMessage($message) {
        $this->message = $message;
    }


}
