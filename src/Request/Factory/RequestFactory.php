<?php

declare(strict_types=1);

namespace Gilek\Ewus\Request\Factory;

use Gilek\Ewus\Client\Credentials;
use Gilek\Ewus\Misc\Factory\DateTimeFactory;
use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;

class RequestFactory implements RequestFactoryInterface
{
    private XmlWriterFactory $xmlWriterFactory;
    private DateTimeFactory $dateTimeFactory;

    public function __construct(?XmlWriterFactory $xmlWriterFactory = null, ?DateTimeFactory $dateTimeFactory = null)
    {
        $this->xmlWriterFactory = $xmlWriterFactory ?? new XmlWriterFactory();
        $this->dateTimeFactory = $dateTimeFactory ?? new DateTimeFactory();
    }

    #[\Override]
    public function createLogin(Credentials $credentials): Request
    {
        return (new LoginRequestFactory($this->xmlWriterFactory))->create($credentials);
    }

    #[\Override]
    public function createLogout(Session $session): Request
    {
        return (new LogoutRequestFactory($this->xmlWriterFactory))->create($session);
    }

    #[\Override]
    public function createChangePassword(Session $session, Credentials $credentials, string $newPassword): Request
    {
        return (new ChangePasswordRequestFactory($this->xmlWriterFactory))
            ->create($session, $credentials, $newPassword);
    }

    #[\Override]
    public function createCheckCwu(Session $session, string $pesel): Request
    {
        return (new CheckCwuRequestFactory($this->xmlWriterFactory, $this->dateTimeFactory))
            ->create($session, $pesel);
    }
}
