<?php

namespace gilek\ewus\responses;

class CheckPeselResponse extends Response
{
    const DATA_STATUS = 1;

    const DATA_OPERATION_ID = 2;

    const DATA_PATIENT_NAME = 3;

    const DATA_PATIENT_SURNAME = 4;

    const DATA_PROVIDER = 5;    
    
    const STATUS_GOOD = 1;

    const STATUS_BAD = 0;

    const STATUS_OUT_OF_DATE = -1;

    const STATUS_NOT_EXIST = 99;

    private $data = [];
    
    /**
     * 
     * @param integer $status
     * @param boolean $strict
     * @return string
     * @throws Exception
     */
    public static function getStatus($status, $strict = false)
    {
        $data = array(
            self::STATUS_GOOD => 'Ubezpieczony',
            self::STATUS_BAD => 'Nieubezpieczony',
            self::STATUS_OUT_OF_DATE => 'Nieaktualny',
            self::STATUS_NOT_EXIST => 'Nieznany',
        );
        if (!array_key_exists($status, $data)) {
            if ($strict) {
                throw new Exception('Nieprawidłowa wartość statusu.');
            } else {
                $status = self::STATUS_NOT_EXIST;
            }
        }    
        return $data[$status];
    }    
    
    /**
     * 
     * @param string $section
     * @return mixed
     */
    function getData($section = null) {
        return $section === null ? $this->data : $this->data[$section];
    }

    /**
     * 
     * @param mixed $data
     * @param string $section
     */
    function setData($data, $section = null) {
        if($section === null) {
            $this->data = $data;
        } else {
            $this->data[$section] = $data;
        }
    }


}


