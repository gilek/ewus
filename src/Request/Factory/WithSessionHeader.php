<?php

declare(strict_types=1);

namespace Gilek\Ewus\Request\Factory;

use Gilek\Ewus\Response\Session;

trait WithSessionHeader
{
    /**
     * @return array<array<mixed>>
     */
    private function generateSessionHeaders(Session $session, string $ns): array
    {
        $comNs = '{' . $ns . '}';

        return [
            [
                'name' =>  $comNs . 'session',
                'attributes' => [
                    'id' => $session->getSessionId()
                ]
            ],
            [
                'name' =>  $comNs . 'authToken',
                'attributes' => [
                    'id' => $session->getToken()
                ]
            ],
        ];
    }
}
