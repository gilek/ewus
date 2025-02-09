<?php

declare(strict_types=1);

namespace Gilek\Ewus\Server;

interface ServerBrokerInterface
{
    public function resolve(string $name): string;
}
