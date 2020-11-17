<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Credentials;

/** Class LoginRequest */
class LoginRequest implements RequestInterface
{
    /** @var Credentials */
    private $credentials;

    /**
     * @param Credentials $credentials
     */
    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        // TODO: Implement getBody() method.
    }
}
