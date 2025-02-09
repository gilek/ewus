<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class LogoutResponse
{
    private string $returnMessage;

    public function __construct(string $returnMessage)
    {
        $this->returnMessage = $returnMessage;
    }

    public function getReturnMessage(): string
    {
        return $this->returnMessage;
    }
}
