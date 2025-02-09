<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class PatientInformation
{
    private string $code;
    private int $level;
    private string $information;

    public function __construct(string $code, int $level, string $information)
    {
        $this->code = $code;
        $this->level = $level;
        $this->information = $information;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getInformation(): string
    {
        return $this->information;
    }
}
