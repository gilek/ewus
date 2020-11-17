<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response;

class LoginResponse extends Response implements SessionInterface
{
    /** @var string */
    private $sessionId;
    
    /** @var string */
    private $token;
    
    /** @var string */
    private $loginMessage;
    
    /** @var string */
    private $loginMessageCode;
    
    /**
     * {@inheritDoc}
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * {@inheritDoc}
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $sessionId
     */
    public function setSessionId(string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLogin(): string
    {
        return $this->getOperation()->getLogin();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getPassword(): string
    {
        return $this->getOperation()->getPassword();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getLoginParams(): string
    {
        return $this->getOperation()->getParams();
    }    
    
    /**
     * TODO not used
     * @return string
     */
    public function getLoginMessage()
    {
        return $this->loginMessage;
    }
    
    /**
     * TODO NOT USED
     * @return string
     */
    public function getLoginMessageCode(): string
    {
        return $this->loginMessageCode;
    }

    /**
     * @param string $loginMessage
     */
    public function setLoginMessage(string $loginMessage): void
    {
        $this->loginMessage = $loginMessage;
    }

    /**
     * 
     * @param string $loginMessageCode
     */
    public function setLoginMessageCode(string $loginMessageCode): void
    {
        $this->loginMessageCode = $loginMessageCode;
    }
}
