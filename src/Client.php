<?php

namespace Gilek\Ewus;

use Gilek\Ewus\Driver\DriverInterface;
use Gilek\Ewus\Driver\SoapDriver;
use Gilek\Ewus\Request\ChangePasswordRequestFactory;
use Gilek\Ewus\Request\CheckCwuRequestFactory;
use Gilek\Ewus\Request\LoginRequestFactory;
use Gilek\Ewus\Request\LogoutRequestFactory;
use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Request\RequestFactory;
use Gilek\Ewus\Request\RequestInterface;
use Gilek\Ewus\Response\ChangePasswordResponse;
use Gilek\Ewus\Response\CheckCwuResponse;
use Gilek\Ewus\Response\LoginResponse;
use Gilek\Ewus\Response\LogoutResponse;
use Gilek\Ewus\Response\ResponseFactory;
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

    /** @var RequestFactory */
    private $requestFactory;

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
        $this->requestFactory = new RequestFactory();
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
        $request = $this->requestFactory->createLogin($this->credentials);

        return $this->responseFactory->createLogin(
            $this->doRequest($request)
        );
    }

    /**
     * @return LogoutResponse
     */
    public function logout(): LogoutResponse
    {
        if (!$this->isAuthorized()) {
            // TODO exception
            return;
        }
        $request = $this->requestFactory->createLogout($this->session);

        return $this->responseFactory->createLogout(
            $this->doRequest($request)
        );
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

        $request = $this->requestFactory->createChangePassword($this->session, $this->credentials, $newPassword);

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

        $request = $this->requestFactory->createCheckCwu($this->session, $pesel);

        return $this->responseFactory->createCwu(
            $this->doRequest($request)
        );
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function doRequest(Request $request): string
    {
        return $this->driver->doRequest(
            $this->serviceBroker->resolve($request->getMethodName()),
            $request->getBody()
        );
    }
}
