<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class ChangePasswordResponse
{
    public function __construct(private readonly string $returnMessage)
    {
    }

    public function getReturnMessage(): string
    {
        return $this->returnMessage;
    }
}
