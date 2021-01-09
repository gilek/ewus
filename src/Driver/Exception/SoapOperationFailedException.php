<?php
declare(strict_types=1);

namespace Gilek\Ewus\Driver\Exception;

use Gilek\Ewus\EwusExceptionInterface;
use RuntimeException;

class SoapOperationFailedException extends RuntimeException implements EwusExceptionInterface
{
}
