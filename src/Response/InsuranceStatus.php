<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class InsuranceStatus
{
    public function __construct(
        private readonly int $code,
        private readonly bool $dn
    ) {
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
