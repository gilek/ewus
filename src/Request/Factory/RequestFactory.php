<?php
declare(strict_types=1);

namespace Gilek\Ewus\Request\Factory;

use Gilek\Ewus\Client\Credentials;
use Gilek\Ewus\Request\Factory\ChangePasswordRequestFactory;
use Gilek\Ewus\Request\Factory\CheckCwuRequestFactory;
use Gilek\Ewus\Request\Factory\LoginRequestFactory;
use Gilek\Ewus\Request\Factory\LogoutRequestFactory;
use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Request\Factory\RequestFactoryInterface;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;

class RequestFactory implements RequestFactoryInterface
{
    /** @var XmlWriterFactory */
    private $xmlWriterFactory;

    /**
     * @param XmlReaderFactory|null $xmlReaderFactory
     */
    public function __construct(?XmlReaderFactory $xmlReaderFactory = null)
    {
        $this->xmlWriterFactory = $xmlReaderFactory ?? new XmlWriterFactory();
    }

    /**
     * {@inheritDoc}
     */
    public function createLogin(Credentials $credentials): Request
    {
        return (new LoginRequestFactory($this->xmlWriterFactory))->build($credentials);
    }

    /**
     * {@inheritDoc}
     */
    public function createLogout(Session $session): Request
    {
        return (new LogoutRequestFactory($this->xmlWriterFactory))->build($session);
    }

    /**
     * {@inheritDoc}
     */
    public function createChangePassword(Session $session, Credentials $credentials, string $newPassword): Request
    {
        return (new ChangePasswordRequestFactory($this->xmlWriterFactory))
            ->build($session, $credentials, $newPassword);
    }

    /**
     * {@inheritDoc}
     */
    public function createCheckCwu(Session $session, string $pesel): Request
    {
        return (new CheckCwuRequestFactory($this->xmlWriterFactory))
            ->build($session, $pesel);
    }
}
