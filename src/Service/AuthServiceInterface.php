<?php
declare(strict_types=1);

namespace Gilek\Ewus\Service;

class AuthServiceInterface implements ServiceInterface
{
    /**
     * {@inheritDoc}
     */
    public function getUrl(): string
    {
        return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus/services/Auth?wsdl';
    }
}
