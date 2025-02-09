<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class CheckCwuResponse
{
    private Operation $operation;
    private int $statusCode;
    private string $pesel;
    private ?Patient $patient;

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

    public function getOperation(): Operation
    {
        return $this->operation;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getPesel(): string
    {
        return $this->pesel;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }
}
