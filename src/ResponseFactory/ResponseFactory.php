<?php

/**
 * This file is part of Boozt Platform
 * and belongs to Boozt Fashion AB.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

declare(strict_types = 1);

namespace Gilek\Ewus\ResponseFactory;

use Gilek\Ewus\Response\ChangePasswordResponse;
use Gilek\Ewus\Response\CheckCwuResponse;
use Gilek\Ewus\Response\LoginResponse;
use Gilek\Ewus\Response\LogoutResponse;

/** Class ResponseFactory */
class ResponseFactory
{
    public function createLogin(string $body): LoginResponse
    {

    }

    public function createCwu(string $body): CheckCwuResponse
    {

    }

    // TODO do we need this?
    public function createLogout(): LogoutResponse
    {

    }

    public function createChangePassword(string $body): ChangePasswordResponse
    {

    }
}
