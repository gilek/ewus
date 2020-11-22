<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response\Factory;

use Gilek\Ewus\Response\ChangePasswordResponse;
use Gilek\Ewus\Response\CheckCwuResponse;
use Gilek\Ewus\Response\Exception\InvalidResponseException;
use Gilek\Ewus\Response\LoginResponse;
use Gilek\Ewus\Response\LogoutResponse;

interface ResponseFactoryInterface
{
    /**
     * @param string $responseBody
     *
     * @return LoginResponse
     *
     * @throws InvalidResponseException
     */
    public function createLogin(string $responseBody): LoginResponse;

    /**
     * @param string $responseBody
     *
     * @return LogoutResponse
     *
     * @throws InvalidResponseException
     */
    public function createLogout(string $responseBody): LogoutResponse;

    /**
     * @param string $responseBody
     *
     * @return CheckCwuResponse
     *
     * @throws InvalidResponseException
     */
    public function createCheckCwu(string $responseBody): CheckCwuResponse;

    /**
     * @param string $responseBody
     *
     * @return ChangePasswordResponse
     *
     * @throws InvalidResponseException
     */
    public function createChangePassword(string $responseBody): ChangePasswordResponse;
}