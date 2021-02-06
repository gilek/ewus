<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class LoginResponse
{
    /** @var string */
    private $sessionId;

    /** @var string */
    private $token;

    /** @var string */
    private $returnMessage;

    /**
     * @param string $sessionId
     * @param string $token
     * @param string $returnMessage
     */
    public function __construct(string $sessionId, string $token, string $returnMessage)
    {
        $this->sessionId = $sessionId;
        $this->token = $token;
        $this->returnMessage = $returnMessage;
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getReturnMessage(): string
    {
        return $this->returnMessage;
    }
}
