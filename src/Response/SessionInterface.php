<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response;

interface SessionInterface
{
    /**
     * @return string
     */
    public function getSessionId(): string;
    
    /**
     * @return string
     */
    public function getToken(): string;
    
    /**
     * @return string
     */
    public function getLogin(): string;
    
    /**
     * @return string
     */
    public function getPassword(): string;
    
    /**
     * TODO co jest w tym array?
     * @return array
     */
    public function getLoginParams(): array;
}
