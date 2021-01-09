<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response\Service;

use Gilek\Ewus\Ns;
use Gilek\Ewus\Response\Exception\AuthenticationException;
use Gilek\Ewus\Response\Exception\AuthorizationException;
use Gilek\Ewus\Response\Exception\AuthTokenException;
use Gilek\Ewus\Response\Exception\InputException;
use Gilek\Ewus\Response\Exception\PassExpiredException;
use Gilek\Ewus\Response\Exception\ServerException;
use Gilek\Ewus\Response\Exception\ServerResponseException;
use Gilek\Ewus\Response\Exception\SessionException;
use Gilek\Ewus\Xml\Exception\ElementNotFoundException;
use Gilek\Ewus\Xml\Exception\NamespaceNotRegisteredException;
use Gilek\Ewus\Xml\XmlReader;

class ErrorParserService
{
    private const NS_PREFIX = 'com';

    /**
     * @param XmlReader $xmlReader
     *
     * @throws ServerResponseException
     */
    public function throwErrorIfExist(XmlReader $xmlReader): void
    {
        $nsPrefix = $this->registerErrorNsPrefix($xmlReader);

        // TODO handle this one
        /*
         "<?xml version='1.0' encoding='ISO-8859-1'?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"><soapenv:Body><soapenv:Fault><faultcode>soapenv:Server</faultcode><faultstring>org.apache.axis2.databinding.ADBException: Unexpected subelement {http://xml.kamsoft.pl/ws/kaas/login_types}newPassword</faultstring><detail /></soapenv:Fault></soapenv:Body></soapenv:Envelope>"
*/
        try {
            $code = $xmlReader->getElementValue('//' . $nsPrefix . ':faultcode');
        } catch (ElementNotFoundException $exception) {
            return;
        }

        try {
            $message = html_entity_decode(
                $xmlReader->getElementValue('//' . $nsPrefix . ':faultstring')
            );
        } catch (ElementNotFoundException $exception) {
            $message = 'Unknown server error';
        }

        $exceptionClass = $this->mapCodeToExceptionClass($code);
        throw new $exceptionClass($message);
    }

    /**
     * @param XmlReader $xmlReader
     *
     * @return string
     */
    private function registerErrorNsPrefix(XmlReader $xmlReader): string
    {
        try {
            return $xmlReader->getNamespacePrefix(Ns::COMMON);
        } catch (NamespaceNotRegisteredException $exception) {
            $xmlReader->registerNamespace(self::NS_PREFIX, Ns::COMMON);

            return self::NS_PREFIX;
        }
    }

    /**
     * @param string $code
     *
     * @return string
     */
    private function mapCodeToExceptionClass(string $code): string
    {
        switch ($code) {
            case 'Client.InputException':
                return InputException::class;

            case 'Client.AuthenticationException':
                return AuthenticationException::class;

            case 'Client.AuthorizationException':
                return AuthorizationException::class;

            case 'Client.AuthTokenException':
                return AuthTokenException::class;

            case 'Client.PassExpiredException':
                return PassExpiredException::class;

            case 'Client.ServerException':
                return ServerException::class;

            case 'Client.SessionException':
                return SessionException::class;

            default:
                return ServerResponseException::class;
        }
    }
}
