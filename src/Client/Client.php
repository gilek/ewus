<?php

declare(strict_types=1);

namespace Gilek\Ewus\Client;

use Gilek\Ewus\Client\Exception\ClientNotAuthenticatedException;
use Gilek\Ewus\Driver\DriverInterface;
use Gilek\Ewus\Driver\Exception\SoapOperationFailedException;
use Gilek\Ewus\Driver\NusoapDriver;
use Gilek\Ewus\Request\Factory\RequestFactory;
use Gilek\Ewus\Request\Factory\RequestFactoryInterface;
use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Response\ChangePasswordResponse;
use Gilek\Ewus\Response\CheckCwuResponse;
use Gilek\Ewus\Response\Exception\InvalidResponseException;
use Gilek\Ewus\Response\Exception\ServerResponseException;
use Gilek\Ewus\Response\Factory\ResponseFactory;
use Gilek\Ewus\Response\Factory\ResponseFactoryInterface;
use Gilek\Ewus\Response\LoginResponse;
use Gilek\Ewus\Response\LogoutResponse;
use Gilek\Ewus\Response\Session;
use Gilek\Ewus\Server\ServerBroker;
use Gilek\Ewus\Server\ServerBrokerInterface;

class Client
{
    public const VERSION = '4';

    private readonly Credentials $credentials;
    private readonly DriverInterface $driver;
    private readonly ServerBrokerInterface $serverBroker;
    private readonly RequestFactoryInterface $requestFactory;
    private readonly ResponseFactoryInterface $responseFactory;
    private ?Session $session = null;

    public function __construct(
        Credentials $credentials,
        ?DriverInterface $driver = null,
        ?ServerBrokerInterface $serverBroker = null,
        ?RequestFactoryInterface $requestFactory = null,
        ?ResponseFactoryInterface $responseFactory = null
    ) {
        $this->credentials = $credentials;
        $this->driver = $driver ?? new NusoapDriver();
        $this->serverBroker = $serverBroker ?? new ServerBroker();
        $this->requestFactory = $requestFactory ?? new RequestFactory();
        $this->responseFactory = $responseFactory ?? new ResponseFactory();
    }

    private function isAuthenticated(): bool
    {
        return $this->session !== null;
    }

    /**
     * @throws InvalidResponseException
     * @throws SoapOperationFailedException
     * @throws ServerResponseException
     */
    public function login(): LoginResponse
    {
        $request = $this->requestFactory->createLogin($this->credentials);
        $response = $this->responseFactory->createLogin(
            $this->doRequest($request)
        );
        $this->session = new Session($response->getSessionId(), $response->getToken());

        return $response;
    }

    /**
     * @throws ClientNotAuthenticatedException
     * @throws InvalidResponseException
     * @throws SoapOperationFailedException
     * @throws ServerResponseException
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
     * @throws InvalidResponseException
     * @throws SoapOperationFailedException
     * @throws ServerResponseException
     */
    public function changePassword(string $newPassword): ChangePasswordResponse
    {
        if (!$this->isAuthenticated()) {
            $this->login();
        }

        $request = $this->requestFactory->createChangePassword($this->session, $this->credentials, $newPassword);

        return $this->responseFactory->createChangePassword(
            $this->doRequest($request)
        );
    }

    /**
     * @throws InvalidResponseException
     * @throws SoapOperationFailedException
     * @throws ServerResponseException
     */
    public function checkCwu(string $pesel): CheckCwuResponse
    {
        if (!$this->isAuthenticated()) {
            $this->login();
        }

        $request = $this->requestFactory->createCheckCwu($this->session, $pesel);

        return $this->responseFactory->createCheckCwu(
            $this->doRequest($request)
        );
    }

    /**
     * @throws SoapOperationFailedException
     */
    private function doRequest(Request $request): string
    {
        return $this->driver->doRequest(
            $this->serverBroker->resolve($request->getMethodName()),
            $request->getBody()
        );
    }
}
