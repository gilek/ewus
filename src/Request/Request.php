<?php

declare(strict_types=1);

namespace Gilek\Ewus\Request;

class Request
{
    public function __construct(
        private readonly RequestMethod $methodName,
        private readonly string $body
    ) {
    }

    public function getMethodName(): RequestMethod
    {
        return $this->methodName;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
