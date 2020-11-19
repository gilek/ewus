<?php
declare(strict_types=1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Credentials;
use Gilek\Ewus\Response\XmlReaderFactory;
use Gilek\Ewus\Session;

/** Class RequestBuilder */
class RequestFactory
{
    /** @var XmlWriterFactory */
    private $xmlWriterFactory;

    /**
     * @param XmlReaderFactory|null $xmlReaderFactory
     */
    public function __construct(?XmlReaderFactory $xmlReaderFactory = null)
    {
        $this->xmlWriterFactory = $xmlReaderFactory !== null ? $xmlReaderFactory : new XmlWriterFactory();
    }

    /**
     * @param Credentials $credentials
     *
     * @return Request
     */
    public function createLogin(Credentials $credentials): Request
    {
        return (new LoginRequestFactory($this->xmlWriterFactory))->build($credentials);
    }

    /**
     * @param Session $session
     *
     * @return Request
     */
    public function createLogout(Session $session): Request
    {
        return (new LogoutRequestFactory($this->xmlWriterFactory))->build($session);
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
        return (new ChangePasswordRequestFactory($this->xmlWriterFactory))
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
        return (new CheckCwuRequestFactory($this->xmlWriterFactory))
            ->build($session, $pesel);
    }
}
