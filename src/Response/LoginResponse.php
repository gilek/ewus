<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class LoginResponse
{
    private string $sessionId;
    private string $token;
    private string $returnMessage;

    public function __construct(string $sessionId, string $token, string $returnMessage)
    {
        $this->sessionId = $sessionId;
        $this->token = $token;
        $this->returnMessage = $returnMessage;
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
