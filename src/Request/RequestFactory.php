<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Credentials;
use Gilek\Ewus\Session;
use Gilek\Ewus\Shared\XmlServiceFactory;

/** Class RequestBuilder */
class RequestFactory
{
    /** @var XmlServiceFactory */
    private $xmlServiceFactory;

    public function __construct()
    {
        // TODO hardcoded, interface?
        $this->xmlServiceFactory = new XmlServiceFactory();
    }

    /**
     * @param Credentials $credentials
     *
     * @return Request
     */
    public function createLogin(Credentials $credentials): Request
    {
        return (new LoginRequestFactory($this->xmlServiceFactory))->build($credentials);
    }

    /**
     * @param Session $session
     *
     * @return Request
     */
    public function createLogout(Session $session): Request
    {
        return (new LogoutRequestFactory($this->xmlServiceFactory))->build($session);
    }

    /**
     * @param Session     $session
     * @param Credentials $credentials
     * @param string      $newPassword
     *
     * @return Request
     */
    public function createChangePassword(Session $session, Credentials $credentials, string $newPassword): Request
    {
        return (new ChangePasswordRequestFactory($this->xmlServiceFactory))
            ->build($session, $credentials, $newPassword);
    }

    /**
     * @param Session $session
     * @param string  $pesel
     *
     * @return Request
     */
    public function createCheckCwu(Session $session, string $pesel): Request
    {
        return (new CheckCwuRequestFactory($this->xmlServiceFactory))
            ->build($session, $pesel);
    }
}
