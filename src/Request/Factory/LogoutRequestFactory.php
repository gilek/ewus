<?php

declare(strict_types=1);

namespace Gilek\Ewus\Request\Factory;

use Gilek\Ewus\Ns;
use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Request\RequestMethod;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;

class LogoutRequestFactory
{
    use WithSessionHeader;

    public function __construct(private readonly XmlWriterFactory $xmlWriterFactory)
    {
    }

    public function create(Session $session): Request
    {
        return new Request(RequestMethod::LOGOUT, $this->generateBody($session));
    }

    private function generateBody(Session $session): string
    {
        $xmlService = $this->xmlWriterFactory->create([
            Ns::SOAP => 'soapenv',
            Ns::AUTH => 'auth',
            Ns::COMMON => 'com',
        ]);

        $soapNs = '{' . Ns::SOAP . '}';
        $authNs = '{' . Ns::AUTH . '}';

        return $xmlService->write($soapNs . 'Envelope', [
            $soapNs . 'Header' => $this->generateSessionHeaders($session, Ns::COMMON),
            $soapNs . 'Body' => [
                $authNs .  'logout' => ''
            ]
        ]);
    }
}
