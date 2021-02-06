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
    private const NS_COMMON_PREFIX = 'com';
    private const NS_SOAP_PREFIX = 'soap';

    /**
     * @param XmlReader $xmlReader
     *
     * @throws ServerResponseException
     */
    public function throwErrorIfExist(XmlReader $xmlReader): void
    {
        if (!$this->hasError($xmlReader)) {
            return;
        }

        $this->handleEwusError($xmlReader);
        $this->handleSoapError($xmlReader);
    }

    /**
     * @param XmlReader $xmlReader
     *
     * @return bool
     */
    private function hasError(XmlReader $xmlReader): bool
    {
        $nsPrefix = $this->registerNsPrefix($xmlReader, Ns::SOAP, self::NS_SOAP_PREFIX);

        return $xmlReader->hasElement('//' . $nsPrefix . ':Fault');
    }

    /**
     * @param XmlReader $xmlReader
     *
     * @throws ServerResponseException
     */
    private function handleEwusError(XmlReader $xmlReader): void
    {
        $nsPrefix = $this->registerNsPrefix($xmlReader, Ns::COMMON, self::NS_COMMON_PREFIX);

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
     * @throws ServerResponseException
     */
    private function handleSoapError(XmlReader $xmlReader): void
    {
        $message = trim(
            preg_replace(
                '/\s+/',
                ' ',
                $xmlReader->getElementValue('//faultstring')
            )
        );

        throw new ServerResponseException($message);
    }

    /**
     * @param XmlReader $xmlReader
     * @param string $namespace
     * @param string $prefixCandidate
     *
     * @return string
     */
    private function registerNsPrefix(XmlReader $xmlReader, string $namespace, string $prefixCandidate): string
    {
        try {
            return $xmlReader->getNamespacePrefix($namespace);
        } catch (NamespaceNotRegisteredException $exception) {
            $xmlReader->registerNamespace($prefixCandidate, $namespace);

            return $prefixCandidate;
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
