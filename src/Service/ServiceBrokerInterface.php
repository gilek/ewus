<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Service;

interface ServiceBrokerInterface
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function resolve(string $name): string;
}
