<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class LoginResponse
{
    public function __construct(
        private readonly string $sessionId,
        private readonly string $token,
        private readonly string $returnMessage
    ) {
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getReturnMessage(): string
    {
        return $this->returnMessage;
    }
}
