<?php
declare(strict_types=1);

namespace Gilek\Ewus\Exception;

class ResponseException extends Exception
{
    /** @var string */
    private $type;
    
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }
}
