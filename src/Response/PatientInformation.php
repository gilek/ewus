<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class PatientInformation
{
    public function __construct(
        private readonly string $code,
        private readonly string $level,
        private readonly string $information
    ) {
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLevel(): string
    {
        return $this->level;
    }

    public function getInformation(): string
    {
        return $this->information;
    }
}
