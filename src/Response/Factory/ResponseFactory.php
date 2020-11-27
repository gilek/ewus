<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response\Factory;

use Gilek\Ewus\Misc\Factory\DateTimeFactory;
use Gilek\Ewus\Response\ChangePasswordResponse;
use Gilek\Ewus\Response\CheckCwuResponse;
use Gilek\Ewus\Response\LoginResponse;
use Gilek\Ewus\Response\LogoutResponse;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;

class ResponseFactory implements ResponseFactoryInterface
{
    /** @var XmlReaderFactory */
    private $xmlReaderFactory;

    /** @var DateTimeFactory */
    private $dateTimeFactory;

    /**
     * @param XmlReaderFactory|null $xmlReaderFactory
     * @param DateTimeFactory|null $dateTimeFactory
     */
    public function __construct(?XmlReaderFactory $xmlReaderFactory = null, ?DateTimeFactory $dateTimeFactory = null)
    {
        $this->xmlReaderFactory = $xmlReaderFactory ?? new XmlReaderFactory();
        $this->dateTimeFactory = $dateTimeFactory ?? new DateTimeFactory();
    }

    /**
     * {@inheritDoc}
     */
    public function createLogin(string $responseBody): LoginResponse
    {
        return (new LoginResponseFactory($this->xmlReaderFactory))->build($responseBody);
    }

    /**
     * {@inheritDoc}
     */
    public function createLogout(string $responseBody): LogoutResponse
    {
        return (new LogoutResponseFactory($this->xmlReaderFactory))->build($responseBody);
    }

    /**
     * {@inheritDoc}
     */
    public function createCheckCwu(string $responseBody): CheckCwuResponse
    {
        return (new CheckCwuResponseFactory($this->xmlReaderFactory, $this->dateTimeFactory))->build($responseBody);
    }

    /**
     * {@inheritDoc}
     */
    public function createChangePassword(string $responseBody): ChangePasswordResponse
    {
        return (new ChangePasswordResponseFactory($this->xmlReaderFactory))->build($responseBody);
    }
}
