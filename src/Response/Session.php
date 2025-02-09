<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class Session
{
    public function __construct(
        private readonly string $sessionId,
        private readonly string $token
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
}
