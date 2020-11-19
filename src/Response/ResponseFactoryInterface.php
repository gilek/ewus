<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

interface ResponseFactoryInterface
{
    /**
     * @param string $responseBody
     *
     * @return mixed
     */
    public function build(string $responseBody);
}
