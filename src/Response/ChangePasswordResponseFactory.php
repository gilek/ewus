<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

use Gilek\Ewus\Ns;

class ChangePasswordResponseFactory
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
     * @return ChangePasswordResponse
     *
     * @throws InvalidResponseException
     */
    public function build(string $responseBody): ChangePasswordResponse
    {
        try {
            $xmrReader = $this->xmlReaderFactory->create($responseBody, [
                self::NS_AUTH_PREFIX => Ns::AUTH
            ]);

            return new ChangePasswordResponse(
                $xmrReader->getElementValue('//' . self::NS_AUTH_PREFIX . ':changePasswordReturn[1]')
            );
        } catch (EmptyResponseException | InvalidResponseFormatException | ElementNotFoundException $exception) {
            throw new InvalidResponseException('Invalid "change password" response.', 0, $exception);
        }
    }
}
