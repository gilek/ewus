<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Credentials;
use Gilek\Ewus\Factory\XmlServiceFactory;
use Sabre\Xml\Service;

class LoginRequest extends CredentialRequestBase implements RequestInterface
{
    private const NS_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';
    private const NS_AUTH = 'http://xml.kamsoft.pl/ws/kaas/login_types';

    /** @var Credentials */
    private $credentials;

    /**
     * @param Credentials $credentials
     */
    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodName(): string
    {
        return 'login';
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        $xmlService = (new XmlServiceFactory())->create([
            self::NS_SOAP => 'soapenv',
            self::NS_AUTH => 'auth'
        ]);

        $soapNs = '{' . self::NS_SOAP . '}';
        $authNs = '{' . self::NS_AUTH . '}';

        return $xmlService->write($soapNs . 'Envelope', [
            $soapNs . 'Header' => null,
            $soapNs . 'Body' => [
                $authNs .  'login' => [
                    $authNs . 'credentials' => $this->generateCredentialItems($this->credentials),
                    $authNs . 'password' => $this->credentials->getPassword()
                ]
            ]
        ]);
    }
}
