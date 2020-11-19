<?php
declare(strict_types=1);

namespace Gilek\Ewus\Driver;

use Gilek\Ewus\EwusExceptionInterface;
use RuntimeException;

class WsdlNotFoundException extends RuntimeException implements EwusExceptionInterface
{
}