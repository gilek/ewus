<?php
/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0a
 * @package Ewus
 */
namespace Ewus;

class CWUResponse {
    /**
     * 
     */
    const FLAG_STATUS = 1;
    
    /**
     * 
     */
    const FLAG_OPERATION_ID = 2;
    
    /**
     * 
     */
    const FLAG_RESPONSE = 4;
    
    /**
     * 
     */
    const FLAG_PATIENT_NAME = 8;
    
    /**
     * 
     */
    const FLAG_PATIENT_SURNAME = 16;
    
    /**
     * 
     */
    const FLAG_PROVIDER = 32;    
    
    /**
     *
     * @var array 
     */
    private $_data = array();
    
    /**
     * 
     * @param integer $flag
     * @param mixed $value
     */
    public function setData($flag,$value) {
        $this->_data[$flag] = $value;
    }
    
    /**
     * 
     * @param integer $flag
     * @return mixed
     */
    public function getData($flag=null) {
        if ($flag!==null) {
            if (!array_key_exists($flag, $this->_data))
                return null;
            
            return $this->_data[$flag];
        }
        return $this->_data;
    }
    
    /**
     * 
     * @return boolean
     */
    public function isValid() {
        if ($this->getData(self::FLAG_STATUS)===null)
            return false;

        if (strlen($this->getData(self::FLAG_RESPONSE))===0)
            return false;
                     
        return true;
    }
}
