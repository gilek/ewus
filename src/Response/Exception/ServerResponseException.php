<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response\Exception;

use Gilek\Ewus\EwusExceptionInterface;
use RuntimeException;

class ServerResponseException extends RuntimeException implements EwusExceptionInterface
{
}
