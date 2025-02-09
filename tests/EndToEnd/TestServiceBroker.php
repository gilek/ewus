<?php

declare(strict_types=1);

namespace Gilek\Ewus\Test\EndToEnd;

use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Request\RequestMethod;
use Gilek\Ewus\Server\ServerBrokerInterface;

class TestServiceBroker implements ServerBrokerInterface
{
    public function resolve(RequestMethod $method): string
    {
        if ($method === RequestMethod::CHECK_CWU) {
            return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus-auth-test/services/ServiceBroker';
        }

        return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus-auth-test/services/Auth';
    }
}
