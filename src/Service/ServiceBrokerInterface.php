<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Service;

use Gilek\Ewus\Request\RequestInterface;

interface ServiceBrokerInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return string
     */
    public function resolve(RequestInterface $request): string;
}
