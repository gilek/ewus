<?php
declare(strict_types=1);

namespace Gilek\Ewus\Response;

use Gilek\Ewus\EwusExceptionInterface;
use RuntimeException;

class ElementNotFoundException extends RuntimeException implements EwusExceptionInterface
{
}