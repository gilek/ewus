<?php
declare(strict_types=1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Credentials;

class LoginRequestFactory
{
    use WithCredentialItem;

    private const NS_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';
    private const NS_AUTH = 'http://xml.kamsoft.pl/ws/kaas/login_types';

    /** @var XmlWriterFactory */
    private $xmlWriterFactory;

    /**
     * @param XmlWriterFactory $xmlWriterFactory
     */
    public function __construct(XmlWriterFactory $xmlWriterFactory)
    {
        $this->xmlWriterFactory = $xmlWriterFactory;
    }

    /**
     * @param Credentials $credentials
     *
     * @return Request
     */
    public function build(Credentials $credentials): Request
    {
        return new Request(Request::METHOD_LOGIN, $this->generateBody($credentials));
    }

    /**
     * @param Credentials $credentials
     *
     * @return string
     */
    private function generateBody(Credentials $credentials): string
    {
        $xmlService = $this->xmlWriterFactory->create([
            self::NS_SOAP => 'soapenv',
            self::NS_AUTH => 'auth'
        ]);

        $soapNs = '{' . self::NS_SOAP . '}';
        $authNs = '{' . self::NS_AUTH . '}';

        return $xmlService->write($soapNs . 'Envelope', [
            $soapNs . 'Header' => null,
            $soapNs . 'Body' => [
                $authNs .  'login' => [
                    $authNs . 'credentials' => $this->generateCredentialItems($credentials, self::NS_AUTH),
                    $authNs . 'password' => $credentials->getPassword()
                ]
            ]
        ]);
    }
}
