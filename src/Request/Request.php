<?php

declare(strict_types=1);

namespace Gilek\Ewus\Request;

class Request
{
    // TODO to enum
    public const METHOD_CHECK_CWU = 'checkCwu';
    public const METHOD_LOGIN = 'login';
    public const METHOD_LOGOUT = 'logout';
    public const METHOD_CHANGE_PASSWORD = 'changePassword';

    public function __construct(
        private readonly string $methodName,
        private readonly string $body
    ) {
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
