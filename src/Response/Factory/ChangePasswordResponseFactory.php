<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response\Factory;

use Gilek\Ewus\Ns;
use Gilek\Ewus\Response\ChangePasswordResponse;
use Gilek\Ewus\Response\Exception\EmptyResponseException;
use Gilek\Ewus\Response\Exception\InvalidResponseContentException;
use Gilek\Ewus\Response\Exception\InvalidResponseException;
use Gilek\Ewus\Response\Exception\ServerResponseException;
use Gilek\Ewus\Response\Service\ErrorParserService;
use Gilek\Ewus\Xml\Exception\ElementNotFoundException;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;

class ChangePasswordResponseFactory
{
    private const NS_AUTH_PREFIX = 'auth';

    private XmlReaderFactory $xmlReaderFactory;
    private ErrorParserService $errorParserService;

    /**
     * @param XmlReaderFactory $xmlReaderFactory
     * @param ErrorParserService $errorParserService
     */
    public function __construct(XmlReaderFactory $xmlReaderFactory, ErrorParserService $errorParserService)
    {
        $this->xmlReaderFactory = $xmlReaderFactory;
        $this->errorParserService = $errorParserService;
    }

    /**
     * @param string $responseBody
     *
     * @return ChangePasswordResponse
     *
     * @throws InvalidResponseException
     * @throws ServerResponseException
     */
    public function build(string $responseBody): ChangePasswordResponse
    {
        try {
            $xmlReader = $this->xmlReaderFactory->create($responseBody, [
                self::NS_AUTH_PREFIX => Ns::AUTH
            ]);
            $this->errorParserService->throwErrorIfExist($xmlReader);

            return new ChangePasswordResponse(
                $xmlReader->getElementValue('//' . self::NS_AUTH_PREFIX . ':changePasswordReturn[1]')
            );
        } catch (EmptyResponseException | InvalidResponseContentException | ElementNotFoundException $exception) {
            throw new InvalidResponseException('Invalid "change password" response.', 0, $exception);
        }
    }
}
