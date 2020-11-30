<?php
declare(strict_types=1);

namespace Gilek\Ewus\Xml\Exception;

use Gilek\Ewus\EwusExceptionInterface;
use RuntimeException;

class NamespaceNotRegisteredException extends RuntimeException implements EwusExceptionInterface
{
}