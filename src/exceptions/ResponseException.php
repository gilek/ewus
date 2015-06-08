<?php

namespace gilek\ewus\exceptions;

class ResponseException extends Exception
{
    /**
     *
     * @var string 
     */
    private $type;
    
    /**
     * 
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     * 
     * @param string $type
     */
    public function setType($type) {
        $this->type = $type;
    }


}
