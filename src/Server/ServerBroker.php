<?php

declare(strict_types=1);

namespace Gilek\Ewus\Server;

use Gilek\Ewus\Request\Request;

class ServerBroker implements ServerBrokerInterface
{
    #[\Override]
    public function resolve(string $name): string
    {
        if ($name === Request::METHOD_CHECK_CWU) {
            return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus/services/ServiceBroker';
        }

        return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus/services/Auth';
    }
}
