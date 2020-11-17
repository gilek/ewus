<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response;

class ChangePasswordResponse extends Response
{
    /** @var string */
    private $message;
    
    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
}
