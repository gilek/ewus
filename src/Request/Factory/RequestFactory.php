<?php
declare(strict_types=1);

namespace Gilek\Ewus\Request\Factory;

use Gilek\Ewus\Client\Credentials;
use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;

class RequestFactory implements RequestFactoryInterface
{
    /** @var XmlWriterFactory */
    private $xmlWriterFactory;

    /**
     * @param XmlWriterFactory|null $xmlWriterFactory
     */
    public function __construct(?XmlWriterFactory $xmlWriterFactory = null)
    {
        $this->xmlWriterFactory = $xmlWriterFactory ?? new XmlWriterFactory();
    }

    /**
     * {@inheritDoc}
     */
    public function createLogin(Credentials $credentials): Request
    {
        return (new LoginRequestFactory($this->xmlWriterFactory))->create($credentials);
    }

    /**
     * {@inheritDoc}
     */
    public function createLogout(Session $session): Request
    {
        return (new LogoutRequestFactory($this->xmlWriterFactory))->create($session);
    }

    /**
     * {@inheritDoc}
     */
    public function createChangePassword(Session $session, Credentials $credentials, string $newPassword): Request
    {
        return (new ChangePasswordRequestFactory($this->xmlWriterFactory))
            ->create($session, $credentials, $newPassword);
    }

    /**
     * {@inheritDoc}
     */
    public function createCheckCwu(Session $session, string $pesel): Request
    {
        return (new CheckCwuRequestFactory($this->xmlWriterFactory))
            ->create($session, $pesel);
    }
}
