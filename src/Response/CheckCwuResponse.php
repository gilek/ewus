<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response;

use DateTimeInterface;

class CheckCwuResponse
{
    public const STATUS_INSURED = 1;
    public const STATUS_UNINSURED = 0;
    public const STATUS_NOT_EXIST = -1;

    /** @var Operation */
    private $operation;

    /** @var int */
    private $statusCode;

    /** @var string */
    private $pesel;

    /** @var Patient|null */
    private $patient;

    /**
     * @param Operation    $operation
     * @param int         $statusCode
     * @param string       $pesel
     * @param Patient|null $patient
     */
    public function __construct(
        Operation $operation,
        int $statusCode,
        string $pesel,
        ?Patient $patient
    ) {
        $this->operation = $operation;
        $this->statusCode = $statusCode;
        $this->pesel = $pesel;
        $this->patient = $patient;
    }

    /**
     * @return Operation
     */
    public function getOperation(): Operation
    {
        return $this->operation;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return string
     */
    public function getPesel(): string
    {
        return $this->pesel;
    }

    /**
     * @return Patient|null
     */
    public function getPatient(): ?Patient
    {
        return $this->patient;
    }
}


