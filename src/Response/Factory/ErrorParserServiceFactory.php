<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response\Factory;

use Gilek\Ewus\Response\Service\ErrorParserService;

class ErrorParserServiceFactory
{
    /**
     * @return ErrorParserService
     */
    public function create(): ErrorParserService
    {
        return new ErrorParserService();
    }
}
