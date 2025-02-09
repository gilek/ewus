<?php

declare(strict_types=1);

namespace Gilek\Ewus\Request\Factory;

use Gilek\Ewus\Client\Credentials;
use Gilek\Ewus\Ns;
use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;

class LoginRequestFactory
{
    use WithCredentialItem;

    public function __construct(private readonly XmlWriterFactory $xmlWriterFactory)
    {
    }

    public function create(Credentials $credentials): Request
    {
        return new Request(Request::METHOD_LOGIN, $this->generateBody($credentials));
    }

    private function generateBody(Credentials $credentials): string
    {
        $xmlService = $this->xmlWriterFactory->create([
            Ns::SOAP => 'soapenv',
            Ns::AUTH => 'auth'
        ]);

        $soapNs = '{' . Ns::SOAP . '}';
        $authNs = '{' . Ns::AUTH . '}';

        return $xmlService->write($soapNs . 'Envelope', [
            $soapNs . 'Header' => null,
            $soapNs . 'Body' => [
                $authNs .  'login' => [
                    $authNs . 'credentials' => $this->generateCredentialItems($credentials, Ns::AUTH),
                    $authNs . 'password' => $credentials->getPassword()
                ]
            ]
        ]);
    }
}
