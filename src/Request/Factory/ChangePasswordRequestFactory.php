<?php
declare(strict_types=1);

namespace Gilek\Ewus\Request\Factory;

use Gilek\Ewus\Client\Credentials;
use Gilek\Ewus\Ns;
use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;

class ChangePasswordRequestFactory
{
    use WithCredentialItem;
    use WithSessionHeader;

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
    public function create(Session $session, Credentials $credentials, string $newPassword): Request
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
            Ns::SOAP => 'soapenv',
            Ns::AUTH => 'auth',
            Ns::COMMON => 'com'
        ]);

        $soapNs = '{' . Ns::SOAP . '}';
        $authNs = '{' . Ns::AUTH . '}';

        return $xmlService->write($soapNs . 'Envelope', [
            $soapNs . 'Header' => $this->generateSessionHeaders($session, Ns::COMMON),
            $soapNs . 'Body' => [
                $authNs .  'login' => [
                    $authNs . 'credentials' => $this->generateCredentialItems($credentials, Ns::AUTH),
                    $authNs . 'newPassword' => $newPassword,
                    $authNs . 'newPasswordRepeat' => $newPassword,
                ]
            ]
        ]);
    }
}
