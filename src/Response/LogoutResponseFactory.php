<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

class LogoutResponseFactory
{
    private const NS_AUTH = 'auth';
    private const NS_AUTH_URL = 'http://xml.kamsoft.pl/ws/kaas/login_types';

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
     */
    public function build(string $responseBody): LogoutResponse
    {
        $xmrReader = $this->xmlReaderFactory->create($responseBody, [
            self::NS_AUTH => self::NS_AUTH_URL
        ]);

        // TODO handle exception
        return new LogoutResponse(
            $xmrReader->getElementValue('//' . self::NS_AUTH . ':logoutReturn')
        );
    }
}
