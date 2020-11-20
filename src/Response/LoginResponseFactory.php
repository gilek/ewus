<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

use Gilek\Ewus\Ns;

class LoginResponseFactory
{
    private const NS_AUTH_PREFIX = 'auth';
    private const NS_COMMON_PREFIX = 'com';

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
     * @return LoginResponse
     *
     * @throws InvalidResponseException
     */
    public function build(string $responseBody): LoginResponse
    {
        try {
            $xmrReader = $this->xmlReaderFactory->create($responseBody, [
                self::NS_AUTH_PREFIX => Ns::AUTH,
                self::NS_COMMON_PREFIX => Ns::COMMON,
            ]);

            return new LoginResponse(
                $xmrReader->getElementAttribute('//' . self::NS_COMMON_PREFIX . ':session', 'id'),
                $xmrReader->getElementAttribute('//' . self::NS_COMMON_PREFIX . ':authToken', 'id'),
                $xmrReader->getElementValue('//' . self::NS_AUTH_PREFIX . ':loginReturn')
            );
        } catch (EmptyResponseException | InvalidResponseFormatException | ElementNotFoundException $exception) {
            throw new InvalidResponseException('Invalid "login" response.', 0, $exception);
        }
    }
}
