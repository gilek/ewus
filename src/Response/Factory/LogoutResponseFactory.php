<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response\Factory;

use Gilek\Ewus\Ns;
use Gilek\Ewus\Response\Exception\EmptyResponseException;
use Gilek\Ewus\Response\Exception\InvalidResponseContentException;
use Gilek\Ewus\Response\Exception\InvalidResponseException;
use Gilek\Ewus\Response\Exception\ServerResponseException;
use Gilek\Ewus\Response\LogoutResponse;
use Gilek\Ewus\Response\Service\ErrorParserService;
use Gilek\Ewus\Xml\Exception\ElementNotFoundException;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;

class LogoutResponseFactory
{
    private const NS_AUTH_PREFIX = 'auth';

    private XmlReaderFactory $xmlReaderFactory;
    private ErrorParserService $errorParserService;

    public function __construct(XmlReaderFactory $xmlReaderFactory, ErrorParserService $errorParserService)
    {
        $this->xmlReaderFactory = $xmlReaderFactory;
        $this->errorParserService = $errorParserService;
    }

    /**
     * @throws InvalidResponseException
     * @throws ServerResponseException
     */
    public function build(string $responseBody): LogoutResponse
    {
        try {
            $xmlReader = $this->xmlReaderFactory->create($responseBody, [
                self::NS_AUTH_PREFIX => Ns::AUTH
            ]);
            $this->errorParserService->throwErrorIfExist($xmlReader);

            return new LogoutResponse(
                $xmlReader->getElementValue('//' . self::NS_AUTH_PREFIX . ':logoutReturn')
            );
        } catch (EmptyResponseException | InvalidResponseContentException | ElementNotFoundException $exception) {
            throw new InvalidResponseException('Invalid "logout" response.', 0, $exception);
        }
    }
}
