<?php

namespace Gilek\Ewus\Client;

use Gilek\Ewus\Client\Exception\ClientNotAuthenticatedException;
use Gilek\Ewus\Driver\DriverInterface;
use Gilek\Ewus\Driver\Exception\WsdlNotFoundException;
use Gilek\Ewus\Driver\SoapDriver;
use Gilek\Ewus\Request\Factory\RequestFactory;
use Gilek\Ewus\Request\Factory\RequestFactoryInterface;
use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Response\ChangePasswordResponse;
use Gilek\Ewus\Response\CheckCwuResponse;
use Gilek\Ewus\Response\Exception\InvalidResponseException;
use Gilek\Ewus\Response\Factory\ResponseFactory;
use Gilek\Ewus\Response\Factory\ResponseFactoryInterface;
use Gilek\Ewus\Response\LoginResponse;
use Gilek\Ewus\Response\LogoutResponse;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Server\ServerBroker;
use Gilek\Ewus\Server\ServerBrokerInterface;

class Client
{
    public const VERSION = '<VERSION>';

    /** @var Credentials */
    private $credentials;

    /** @var Session|null */
    private $session;

    /** @var DriverInterface */
    private $driver;

    /** @var ServerBrokerInterface */
    private $serverBroker;

    /** @var RequestFactoryInterface */
    private $requestFactory;

    /** @var ResponseFactory */
    private $responseFactory;

    /**
     * @param Credentials $credentials
     * @param DriverInterface|null $driver
     * @param ServerBrokerInterface|null $serverBroker
     * @param RequestFactoryInterface|null $requestFactory
     * @param ResponseFactoryInterface|null $responseFactory
     */
    public function __construct(
        Credentials $credentials,
        ?DriverInterface $driver = null,
        ?ServerBrokerInterface $serverBroker = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?ResponseFactoryInterface $responseFactory = null
    )
    {
        $this->credentials = $credentials;
        $this->driver = $driver ?? new SoapDriver();
        $this->serverBroker = $serverBroker ?? new ServerBroker();
        $this->requestFactory = $requestFactory ?? new RequestFactory();
        $this->responseFactory = $responseFactory ?? new ResponseFactory();
    }

    /**
     * @return bool
     */
    private function isAuthenticated(): bool
    {
        return $this->session !== null;
    }

    private function authenticate()
    {
        $response = $this->login();
        $this->session = new Session($response->getSessionId(), $response->getToken());
    }

    /**
     *
     * @return LoginResponse
     *
     * @throws InvalidResponseException
     * @throws WsdlNotFoundException
     */
    public function login(): LoginResponse
    {
        $request = $this->requestFactory->createLogin($this->credentials);

        return $this->responseFactory->createLogin(
            $this->doRequest($request)
        );
    }

    /**
     * @return LogoutResponse
     *
     * @throws ClientNotAuthenticatedException
     * @throws InvalidResponseException
     * @throws WsdlNotFoundException
     */
    public function logout(): LogoutResponse
    {
        if (!$this->isAuthenticated()) {
            throw new ClientNotAuthenticatedException('Client has to be logged in to perform this action.');
        }
        $request = $this->requestFactory->createLogout($this->session);

        return $this->responseFactory->createLogout(
            $this->doRequest($request)
        );
    }

    /**
     * @param string $newPassword
     *
     * @return ChangePasswordResponse
     *
     * @throws InvalidResponseException
     * @throws WsdlNotFoundException
     */
    public function changePassword(string $newPassword): ChangePasswordResponse
    {
        if (!$this->isAuthenticated()) {
            $this->authenticate();
        }

        $request = $this->requestFactory->createChangePassword($this->session, $this->credentials, $newPassword);

        return $this->responseFactory->createChangePassword(
            $this->doRequest($request)
        );
    }

    /**
     * @param string $pesel
     *
     * @return CheckCwuResponse
     *
     * @throws InvalidResponseException
     * @throws WsdlNotFoundException
     */
    public function checkCwu(string $pesel): CheckCwuResponse
    {
        if (!$this->isAuthenticated()) {
            $this->authenticate();
        }

        $request = $this->requestFactory->createCheckCwu($this->session, $pesel);

        return $this->responseFactory->createCheckCwu(
            $this->doRequest($request)
        );
    }

    /**
     * @param Request $request
     *
     * @return string
     *
     * @throws WsdlNotFoundException
     */
    public function doRequest(Request $request): string
    {
        return $this->driver->doRequest(
            $this->serverBroker->resolve($request->getMethodName()),
            $request->getBody()
        );
    }
}