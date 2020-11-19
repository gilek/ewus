<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

class ResponseFactory
{
    /**
     * @param string $body
     *
     * @return LoginResponse
     */
    public function createLogin(string $body): LoginResponse
    {
        return (new LoginResponseFactory())->build($body);
    }

    /**
     * @param string $body
     *
     * @return LogoutResponse
     */
    public function createLogout(string $body): LogoutResponse
    {
        return (new LogoutResponseFactory())->build($body);
    }

    /**
     * @param string $body
     *
     * @return CheckCwuResponse
     */
    public function createCheckCwu(string $body): CheckCwuResponse
    {
        return (new CheckCwuResponseFactory())->build($body);
    }

    /**
     * @param string $body
     *
     * @return ChangePasswordResponse
     */
    public function createChangePassword(string $body): ChangePasswordResponse
    {
        return (new ChangePasswordResponseFactory())->build($body);
    }
}
