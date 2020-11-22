<?php
declare(strict_types=1);

namespace Gilek\Ewus\Server;

use Gilek\Ewus\Request\Request;

class ServerBroker implements ServerBrokerInterface
{
    /**
     * {@inheritDoc}
     */
    public function resolve(string $name): string
    {
        if ($name === Request::METHOD_CHECK_CWU) {
            return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus/services/SerwerBroker?wsdl';
        }

        return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus/services/Auth?wsdl';
    }
}
