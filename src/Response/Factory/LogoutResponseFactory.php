<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response\Factory;

use Gilek\Ewus\Ns;
use Gilek\Ewus\Response\Exception\EmptyResponseException;
use Gilek\Ewus\Response\Exception\InvalidResponseContentException;
use Gilek\Ewus\Response\Exception\InvalidResponseException;
use Gilek\Ewus\Response\LogoutResponse;
use Gilek\Ewus\Xml\Exception\ElementNotFoundException;
use Gilek\Ewus\Xml\Factory\XmlReaderFactory;

class LogoutResponseFactory
{
    private const NS_AUTH_PREFIX = 'auth';

    /** @var XmlReaderFactory */
    private $xmlReaderFactory;

    /**
     * @param XmlReaderFactory $xmlReaderFactory
     */
    public function __construct(XmlReaderFactory $xmlReaderFactory)
    {
        $this->xmlReaderFactory = $xmlReaderFactory;
    }

    /**
     * @param string $responseBody
     *
     * @return LogoutResponse
     *
     * @throws InvalidResponseException
     */
    public function build(string $responseBody): LogoutResponse
    {
        try {
            $xmrReader = $this->xmlReaderFactory->create($responseBody, [
                self::NS_AUTH_PREFIX => Ns::AUTH
            ]);

            return new LogoutResponse(
                $xmrReader->getElementValue('//' . self::NS_AUTH_PREFIX . ':logoutReturn')
            );
        } catch (EmptyResponseException | InvalidResponseContentException | ElementNotFoundException $exception) {
            throw new InvalidResponseException('Invalid "logout" response.', 0, $exception);
        }
    }
}
