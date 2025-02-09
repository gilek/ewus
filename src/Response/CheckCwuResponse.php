<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class CheckCwuResponse
{
    public function __construct(
        private readonly Operation $operation,
        private readonly int $statusCode,
        private readonly string $pesel,
        private readonly ?Patient $patient
    ) {
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
