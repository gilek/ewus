<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Credentials;
use Gilek\Ewus\Shared\XmlServiceFactory;

class LoginRequestFactory
{
    use WithCredentialItem;

    private const NS_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';
    private const NS_AUTH = 'http://xml.kamsoft.pl/ws/kaas/login_types';

    /** @var XmlServiceFactory */
    private $xmlServiceFactory;

    /**
     * @param XmlServiceFactory $xmlServiceFactory
     */
    public function __construct(XmlServiceFactory $xmlServiceFactory)
    {
        $this->xmlServiceFactory = $xmlServiceFactory;
    }

    /**
     * @param Credentials $credentials
     *
     * @return Request
     */
    public function build(Credentials $credentials): Request
    {
        return new Request('login', $this->generateBody($credentials));
    }

    /**
     * @param Credentials $credentials
     *
     * @return string
     */
    private function generateBody(Credentials $credentials): string
    {
        $xmlService = $this->xmlServiceFactory->create([
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
