<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

class ChangePasswordResponseFactory
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
     * @return ChangePasswordResponse
     */
    public function build(string $responseBody): ChangePasswordResponse
    {
        $xmrReader = $this->xmlReaderFactory->create($responseBody, [
            self::NS_AUTH => self::NS_AUTH_URL
        ]);

        // TODO handle exception
        return new ChangePasswordResponse(
            $xmrReader->getElementValue('//' . self::NS_AUTH . ':changePasswordReturn')
        );
    }
}
