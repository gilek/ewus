<?php

declare(strict_types=1);

namespace Gilek\Ewus\Request\Factory;

use Gilek\Ewus\Client\Credentials;
use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Response\Session;

interface RequestFactoryInterface
{
    public function createLogin(Credentials $credentials): Request;

    public function createLogout(Session $session): Request;

    public function createChangePassword(Session $session, Credentials $credentials, string $newPassword): Request;

    public function createCheckCwu(Session $session, string $pesel): Request;
}
