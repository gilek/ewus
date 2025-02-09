<?php

declare(strict_types=1);

namespace Gilek\Ewus\Server;

use Gilek\Ewus\Request\RequestMethod;

class ServerBroker implements ServerBrokerInterface
{
    #[\Override]
    public function resolve(RequestMethod $method): string
    {
        if ($method === RequestMethod::CHECK_CWU) {
            return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus/services/ServiceBroker';
        }

        return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus/services/Auth';
    }
}
