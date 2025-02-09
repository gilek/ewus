<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response\Factory;

use Gilek\Ewus\Ns;
use Gilek\Ewus\Response\Exception\EmptyResponseException;
use Gilek\Ewus\Response\Exception\InvalidResponseContentException;
use Gilek\Ewus\Response\Exception\InvalidResponseException;
use Gilek\Ewus\Response\Exception\ServerResponseException;
use Gilek\Ewus\Response\LoginResponse;
use Gilek\Ewus\Response\Service\ErrorParserService;
use Gilek\Ewus\Xml\Exception\ElementNotFoundException;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;
use Gilek\Ewus\Xml\XmlReader;

class LoginResponseFactory
{
    private const NS_AUTH_PREFIX = 'auth';
    private const NS_COMMON_PREFIX = 'com';

    private XmlReaderFactory $xmlReaderFactory;
    private ErrorParserService $errorParserService;

    public function __construct(XmlReaderFactory $xmlReaderFactory, ErrorParserService $errorParserService)
    {
        $this->xmlReaderFactory = $xmlReaderFactory;
        $this->errorParserService = $errorParserService;
    }

    /**
     * @param string $responseBody
     *
     * @return LoginResponse
     *
     * @throws InvalidResponseException
     * @throws ServerResponseException
     */
    public function build(string $responseBody): LoginResponse
    {
        try {
            $xmlReader = $this->xmlReaderFactory->create($responseBody, [
                self::NS_AUTH_PREFIX => Ns::AUTH,
                self::NS_COMMON_PREFIX => Ns::COMMON,
            ]);

            $this->errorParserService->throwErrorIfExist($xmlReader);

            return new LoginResponse(
                $xmlReader->getElementAttribute('//' . self::NS_COMMON_PREFIX . ':session', 'id'),
                $xmlReader->getElementAttribute('//' . self::NS_COMMON_PREFIX . ':authToken', 'id'),
                $this->extractReturnMessage($xmlReader)
            );
        } catch (EmptyResponseException | InvalidResponseContentException | ElementNotFoundException $exception) {
            throw new InvalidResponseException('Invalid "login" response.', 0, $exception);
        }
    }

    /**
     * @param XmlReader $xmrReader
     *
     * @return string
     *
     * @throws ElementNotFoundException
     */
    private function extractReturnMessage(XmlReader $xmrReader): string
    {
        $returnMessage = $xmrReader->getElementValue('//' . self::NS_AUTH_PREFIX . ':loginReturn');

        return html_entity_decode($returnMessage);
    }
}
