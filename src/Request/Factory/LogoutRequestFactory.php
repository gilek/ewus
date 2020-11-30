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
     * @param Session $session
     *
     * @return Request
     */
    public function create(Session $session): Request
    {
        return new Request(Request::METHOD_LOGOUT, $this->generateBody($session));
    }

    /**
     * @param Session $session
     *
     * @return string
     */
    private function generateBody(Session $session)
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
