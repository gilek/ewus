<?php
declare(strict_types = 1);

namespace Gilek\Ewus;

// TODO tkink about better plase
class Session
{
    /** @var string */
    private $sessionId;

    /** @var string */
    private $token;

    /**
     * @param string $sessionId
     * @param string $token
     */
    public function __construct(string $sessionId, string $token)
    {
        $this->sessionId = $sessionId;
        $this->token = $token;
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
}
