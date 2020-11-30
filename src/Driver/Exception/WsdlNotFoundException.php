<?php
declare(strict_types=1);

namespace Gilek\Ewus\Driver\Exception;

use Gilek\Ewus\EwusExceptionInterface;
use RuntimeException;

class WsdlNotFoundException extends RuntimeException implements EwusExceptionInterface
{
}
