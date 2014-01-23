<?php
/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0a
 * @package Ewus
 */
namespace Ewus;

require_once 'Exceptions.php';

class Status {
    /**
     * 
     */
    const STATUS_GOOD = 1;
    
    /**
     * 
     */
    const STATUS_BAD = 0;
    
    /**
     * 
     */
    const STATUS_OUT_OF_DATE = -1;
    
    /**
     * 
     */
    const STATUS_NOT_EXIST = 99;
    
    /**
     * 
     * @param integer $status
     * @param boolean $strict
     * @return string
     * @throws Exception
     */
    public static function getStatus($status,$strict = false) {
        $data = array(
            self::STATUS_GOOD => 'Ubezpieczony',
            self::STATUS_BAD => 'Nieubezpieczony',
            self::STATUS_OUT_OF_DATE =>'Nieaktualny',
            self::STATUS_NOT_EXIST => 'Nieznany',
        );
        if (!array_key_exists($status, $data)) 
            if ($strict)
                throw new Exception('Nieprawidłowa wartość statusu.');
            else    
                $status = self::STATUS_NOT_EXIST;
            
        return $data[$status];
    }
}
