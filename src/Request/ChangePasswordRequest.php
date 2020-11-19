<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Credentials;
use Gilek\Ewus\Factory\XmlServiceFactory;
use Gilek\Ewus\Session;

class ChangePasswordRequest extends CredentialRequestBase implements RequestInterface
{
    private const NS_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';
    private const NS_AUTH = 'http://xml.kamsoft.pl/ws/kaas/login_types';

    /** @var Session */
    private $session;

    /** @var Credentials */
    private $credentials;

    /** @var string */
    private $newPassword;

    /**
     * @param Session     $session
     * @param Credentials $credentials
     * @param string      $newPassword
     */
    public function __construct(Session $session, Credentials $credentials, string $newPassword)
    {
        $this->session = $session;
        $this->credentials = $credentials;
        $this->newPassword = $newPassword;
    }

    /**
     * {@inheritDoc}
     */
    public function getMethodName(): string
    {
        return 'changePassword';
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
                    $authNs . 'newPassword' => $this->newPassword,
                    $authNs . 'newPasswordRepeat' => $this-$this->newPassword,
                ]
            ]
        ]);
    }
}
