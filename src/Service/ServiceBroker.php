<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Service;

use Gilek\Ewus\Request\CheckCwuRequest;

class ServiceBroker implements ServiceBrokerInterface
{
    /**
     * {@inheritDoc}
     */
    public function resolve(string $name): string
    {
        if ($name === CheckCwuRequest::NAME) {
            return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus/services/ServiceBroker?wsdl';
        }

        return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus/services/Auth?wsdl';
    }
}
