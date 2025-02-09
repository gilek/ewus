<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response\Factory;

use Gilek\Ewus\Misc\Factory\DateTimeFactory;
use Gilek\Ewus\Response\ChangePasswordResponse;
use Gilek\Ewus\Response\CheckCwuResponse;
use Gilek\Ewus\Response\LoginResponse;
use Gilek\Ewus\Response\LogoutResponse;
use Gilek\Ewus\Response\Service\ErrorParserService;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;

class ResponseFactory implements ResponseFactoryInterface
{
    private XmlReaderFactory $xmlReaderFactory;
    private ?ErrorParserService $errorParserService;
    private ErrorParserServiceFactory $errorParserServiceFactory;
    private DateTimeFactory $dateTimeFactory;

    public function __construct(
        ?XmlReaderFactory $xmlReaderFactory = null,
        ?ErrorParserServiceFactory $errorParserServiceFactory = null,
        ?DateTimeFactory $dateTimeFactory = null
    ) {
        $this->xmlReaderFactory = $xmlReaderFactory ?? new XmlReaderFactory();
        $this->dateTimeFactory = $dateTimeFactory ?? new DateTimeFactory();
        $this->errorParserServiceFactory = $errorParserServiceFactory ?? new ErrorParserServiceFactory();
    }

    #[\Override]
    public function createLogin(string $responseBody): LoginResponse
    {
        $factory = new LoginResponseFactory(
            $this->xmlReaderFactory,
            $this->getErrorParserService()
        );

        return $factory->build($responseBody);
    }

    #[\Override]
    public function createLogout(string $responseBody): LogoutResponse
    {
        $factory = new LogoutResponseFactory($this->xmlReaderFactory, $this->getErrorParserService());

        return $factory->build($responseBody);
    }

    #[\Override]
    public function createCheckCwu(string $responseBody): CheckCwuResponse
    {
        $factory = new CheckCwuResponseFactory(
            $this->xmlReaderFactory,
            $this->getErrorParserService(),
            $this->dateTimeFactory
        );

        return $factory->build($responseBody);
    }

    #[\Override]
    public function createChangePassword(string $responseBody): ChangePasswordResponse
    {
        $factory = new ChangePasswordResponseFactory($this->xmlReaderFactory, $this->getErrorParserService());

        return $factory->build($responseBody);
    }

    private function getErrorParserService(): ErrorParserService
    {
        if ($this->errorParserService === null) {
            $this->errorParserService = $this->errorParserServiceFactory->create();
        }

        return $this->errorParserService;
    }
}
