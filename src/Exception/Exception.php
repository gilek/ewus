<?php
declare(strict_types=1);

namespace Gilek\Ewus\Exception;

/** TODO unless exception */
class Exception extends \Exception
{
    /**
     * TODO to remove
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }
}
