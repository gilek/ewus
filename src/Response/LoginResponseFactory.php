<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

class LoginResponseFactory
{
    private const NS_AUTH = 'auth';
    private const NS_AUTH_URL = 'http://xml.kamsoft.pl/ws/kaas/login_types';
    private const NS_COMMON = 'com';
    private const NS_COMMON_URL = 'http://xml.kamsoft.pl/ws/common';

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
     */
    public function build(string $responseBody): LoginResponse
    {
        $xmrReader = $this->xmlReaderFactory->create($responseBody, [
            self::NS_AUTH => self::NS_AUTH_URL,
            self::NS_COMMON => self::NS_COMMON_URL,
        ]);

        // TODO handle exception
        return new LoginResponse(
            $xmrReader->getElementAttribute('//' . self::NS_COMMON . ':session', 'id'),
            $xmrReader->getElementAttribute('//' . self::NS_COMMON . ':authToken', 'id'),
            $xmrReader->getElementValue('//' . self::NS_AUTH . ':loginReturn')
        );
    }
}
