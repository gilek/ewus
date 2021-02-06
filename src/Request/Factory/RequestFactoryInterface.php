<?php

declare(strict_types=1);

namespace Gilek\Ewus\Request\Factory;

use Gilek\Ewus\Client\Credentials;
use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Response\Session;

interface RequestFactoryInterface
{
    /**
     * @param Credentials $credentials
     *
     * @return Request
     */
    public function createLogin(Credentials $credentials): Request;

    /**
     * @param Session $session
     *
     * @return Request
     */
    public function createLogout(Session $session): Request;

    /**
     * @param Session     $session
     * @param Credentials $credentials
     * @param string      $newPassword
     *
     * @return Request
     */
    public function createChangePassword(Session $session, Credentials $credentials, string $newPassword): Request;

    /**
     * @param Session $session
     * @param string  $pesel
     *
     * @return Request
     */
    public function createCheckCwu(Session $session, string $pesel): Request;
}
