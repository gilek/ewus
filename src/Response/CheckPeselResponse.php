<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response;

use Gilek\Ewus\Exception\Exception;

class CheckPeselResponse extends Response
{
    public const DATA_STATUS = 1;
    public const DATA_OPERATION_ID = 2;
    public const DATA_PATIENT_NAME = 3;
    public const DATA_PATIENT_SURNAME = 4;
    public const DATA_PROVIDER = 5;
    
    public const STATUS_GOOD = 1;
    public const STATUS_BAD = 0;
    public const STATUS_OUT_OF_DATE = -1;
    public const STATUS_NOT_EXIST = 99;

    /** @var array */
    private $data = [];
    
    /**
     * @param int $status
     * @param bool $strict
     *
     * @return string
     *               
     * @throws Exception
     */
    public static function getStatus(int $status, bool $strict = false)
    {
        // TODO omg
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
     * @param string|null $section
     *
     * @return mixed
     */
    public  function getData(?string $section = null)
    {
        return $section === null ? $this->data : $this->data[$section];
    }

    /**
     *
     * @param mixed       $data
     * @param string|null $section
     */
    public function setData($data, ?string $section = null): void
    {
        // TODO omg
        if ($section === null) {
            $this->data = $data;
        } else {
            $this->data[$section] = $data;
        }
    }
}


