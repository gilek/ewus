<?php

namespace Gilek\Ewus;

use Gilek\Ewus\Driver\DriverInterface;
use Gilek\Ewus\Driver\SoapDriver;
use Gilek\Ewus\Request\ChangePasswordRequest;
use Gilek\Ewus\Request\CheckCwuRequest;
use Gilek\Ewus\Request\LoginRequest;
use Gilek\Ewus\Request\LogoutRequest;
use Gilek\Ewus\Request\RequestInterface;
use Gilek\Ewus\Response\ChangePasswordResponse;
use Gilek\Ewus\Response\CheckCwuResponse;
use Gilek\Ewus\Response\LoginResponse;
use Gilek\Ewus\ResponseFactory\ResponseFactory;
use Gilek\Ewus\Service\ServiceBroker;
use Gilek\Ewus\Service\ServiceBrokerInterface;

class Client
{
    public const VERSION = '2.0';

    /** @var Credentials */
    private $credentials;

    /** @var Session|null */
    private $session;

    /** @var DriverInterface */
    private $driver;

    /** @var ServiceBrokerInterface */
    private $serviceBroker;

    /** @var ResponseFactory */
    private $responseFactory;

    /**
     * @param Credentials          $credentials
     * @param DriverInterface|null $driver
     * @param ServiceBroker|null   $serviceBroker
     */
    public function __construct(
        Credentials $credentials,
        ?DriverInterface $driver = null,
        ?ServiceBroker $serviceBroker = null
    ) {
        $this->credentials = $credentials;
        $this->driver = $driver !== null ? $driver : new SoapDriver();
        $this->serviceBroker = $serviceBroker !== null ? $serviceBroker : new ServiceBroker();
        // Hardcoded
        $this->responseFactory = new ResponseFactory();
    }

    /**
     * @return bool
     */
    private function isAuthorized(): bool
    {
        return $this->session !== null;
    }

    private function authorize()
    {
        $response = $this->login();
        $this->session = new Session($response->getSessionId(), $response->getToken());
    }

    /**
     *
     * @return LoginResponse
     */
    public function login(): LoginResponse
    {
        $request = new LoginRequest($this->credentials);

        return $this->responseFactory->createLogin(
            $this->doRequest($request)
        );
    }

    /**
     * Is bool enought?
     */
    public function logout(): void
    {
        if (!$this->isAuthorized()) {
            return;
        }

        $request = new LogoutRequest($this->session);
        $this->doRequest($request);
    }
    
    /**
     * 
     * @param string $newPassword
     *
     * @return ChangePasswordResponse
     */
    public function changePassword(string $newPassword): ChangePasswordResponse
    {
        if (!$this->isAuthorized()) {
            $this->authorize();
        }

        $request = new ChangePasswordRequest($this->session, $this->credentials, $newPassword);

        return $this->responseFactory->createChangePassword(
            $this->doRequest($request)
        );
    }

    /**
     * @param string $pesel
     *
     * @return CheckCwuResponse
     */
    public function checkCwu(string $pesel): CheckCwuResponse
    {
        if (!$this->isAuthorized()) {
            $this->authorize();
        }

        $request = new CheckCwuRequest($this->session, $pesel);

        return $this->responseFactory->createCwu(
            $this->doRequest($request)
        );
    }

    /**
     * @param RequestInterface $request
     *
     * @return string
     */
    public function doRequest(RequestInterface $request): string
    {
        return $this->driver->doRequest(
            $this->serviceBroker->resolve($request->getMethodName()),
            $request->getBody()
        );
    }
}
