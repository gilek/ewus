<?php

declare(strict_types=1);

namespace Gilek\Ewus\Server;

use Gilek\Ewus\Request\RequestMethod;

interface ServerBrokerInterface
{
    public function resolve(RequestMethod $method): string;
}
