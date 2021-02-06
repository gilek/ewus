<?php

declare(strict_types=1);

namespace Gilek\Ewus\Test\EndToEnd;

use Gilek\Ewus\Request\Request;
use Gilek\Ewus\Server\ServerBrokerInterface;

class TestServiceBroker implements ServerBrokerInterface
{
    /**
     * {@inheritDoc}
     */
    public function resolve(string $name): string
    {
        if ($name === Request::METHOD_CHECK_CWU) {
            return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus-auth-test/services/ServiceBroker?wsdl';
        }

        return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus-auth-test/services/Auth?wsdl';
    }
}
