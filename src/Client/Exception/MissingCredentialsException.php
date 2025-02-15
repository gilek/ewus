<?php

declare(strict_types=1);

namespace Gilek\Ewus\Client\Exception;

use Gilek\Ewus\EwusExceptionInterface;
use RuntimeException;

class MissingCredentialsException extends RuntimeException implements EwusExceptionInterface
{
}
