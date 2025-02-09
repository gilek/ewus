<?php

declare(strict_types=1);

namespace Gilek\Ewus\Request\Factory;

use Gilek\Ewus\Ns;
use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Xml\Factory\XmlWriterFactory;

class LogoutRequestFactory
{
    use WithSessionHeader;

    private XmlWriterFactory $xmlWriterFactory;

    public function __construct(XmlWriterFactory $xmlWriterFactory)
    {
        $this->xmlWriterFactory = $xmlWriterFactory;
    }

    public function create(Session $session): Request
    {
        return new Request(Request::METHOD_LOGOUT, $this->generateBody($session));
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
