<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class Session
{
    private string $sessionId;
    private string $token;

    public function __construct(string $sessionId, string $token)
    {
        $this->sessionId = $sessionId;
        $this->token = $token;
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
