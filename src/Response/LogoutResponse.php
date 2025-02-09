<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class LogoutResponse
{
    public function __construct(private readonly string $returnMessage)
    {
    }

    public function getReturnMessage(): string
    {
        return $this->returnMessage;
    }
}
