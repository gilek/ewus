<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class InsuranceStatus
{
    private int $code;
    private bool $dn;

    public function __construct(int $code, bool $dn)
    {
        $this->code = $code;
        $this->dn = $dn;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function isDn(): bool
    {
        return $this->dn;
    }
}
