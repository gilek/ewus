<?php
declare(strict_types=1);

namespace Gilek\Ewus\Request;

use Gilek\Ewus\Credentials;
use Gilek\Ewus\Session;

class ChangePasswordRequestFactory
{
    use WithCredentialItem;
    use WithSessionHeader;

    private const NS_SOAP = 'http://schemas.xmlsoap.org/soap/envelope/';
    private const NS_AUTH = 'http://xml.kamsoft.pl/ws/kaas/login_types';
    private const NS_COMMON = 'http://xml.kamsoft.pl/ws/common';

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
     * @param Session     $session
     * @param Credentials $credentials
     * @param string      $newPassword
     *
     * @return Request
     */
    public function build(Session $session, Credentials $credentials, string $newPassword): Request
    {
        return new Request(
            Request::METHOD_CHANGE_PASSWORD,
            $this->generateBody($session, $credentials, $newPassword)
        );
    }

    /**
     * @param Session     $session
     * @param Credentials $credentials
     * @param string      $newPassword
     *
     * @return string
     */
    private function generateBody(Session $session, Credentials $credentials, string $newPassword): string
    {
        $xmlService = $this->xmlWriterFactory->create([
            self::NS_SOAP => 'soapenv',
            self::NS_AUTH => 'auth',
            self::NS_COMMON => 'com'
        ]);

        $soapNs = '{' . self::NS_SOAP . '}';
        $authNs = '{' . self::NS_AUTH . '}';

        return $xmlService->write($soapNs . 'Envelope', [
            $soapNs . 'Header' => $this->generateSessionHeaders($session, self::NS_COMMON),
            $soapNs . 'Body' => [
                $authNs .  'login' => [
                    $authNs . 'credentials' => $this->generateCredentialItems($credentials, self::NS_AUTH),
                    $authNs . 'newPassword' => $newPassword,
                    $authNs . 'newPasswordRepeat' => $newPassword,
                ]
            ]
        ]);
    }
}
