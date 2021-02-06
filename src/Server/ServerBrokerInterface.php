<?php

declare(strict_types=1);

namespace Gilek\Ewus\Server;

interface ServerBrokerInterface
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function resolve(string $name): string;
}
