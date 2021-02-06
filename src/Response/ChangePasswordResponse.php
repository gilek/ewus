<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

class ChangePasswordResponse
{
    /** @var string */
    private $returnMessage;

    /**
     * @param string $returnMessage
     */
    public function __construct(string $returnMessage)
    {
        $this->returnMessage = $returnMessage;
    }

    /**
     * @return string
     */
    public function getReturnMessage(): string
    {
        return $this->returnMessage;
    }
}
