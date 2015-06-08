<?php

namespace gilek\ewus\services;

class AuthService implements Service
{
    /**
     * 
     * @inheritdoc
     */
    public function getUrl() {
        return 'https://ewus.nfz.gov.pl/ws-broker-server-ewus/services/Auth?wsdl';
    }
}