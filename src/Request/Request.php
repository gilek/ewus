<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

class Request
{
    /** @var string */
    private $methodName;

    /** @var string */
    private $body;

    /**
     * @param string $methodName
     * @param string $body
     */
    public function __construct(string $methodName, string $body)
    {
        $this->methodName = $methodName;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }
}
