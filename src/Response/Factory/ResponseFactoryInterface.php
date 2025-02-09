<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response\Factory;

use Gilek\Ewus\Response\ChangePasswordResponse;
use Gilek\Ewus\Response\CheckCwuResponse;
use Gilek\Ewus\Response\Exception\InvalidResponseException;
use Gilek\Ewus\Response\Exception\ServerResponseException;
use Gilek\Ewus\Response\LoginResponse;
use Gilek\Ewus\Response\LogoutResponse;

interface ResponseFactoryInterface
{
    /**
     * @throws InvalidResponseException
     * @throws ServerResponseException
     */
    public function createLogin(string $responseBody): LoginResponse;

    /**
     * @throws InvalidResponseException
     * @throws ServerResponseException
     */
    public function createLogout(string $responseBody): LogoutResponse;

    /**
     * @throws InvalidResponseException
     * @throws ServerResponseException
     */
    public function createCheckCwu(string $responseBody): CheckCwuResponse;

    /**
     * @throws InvalidResponseException
     * @throws ServerResponseException
     */
    public function createChangePassword(string $responseBody): ChangePasswordResponse;
}
