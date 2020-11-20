<?php
declare(strict_types = 1);

namespace Gilek\Ewus\Response;

class PatientInformation
{
    /** @var string */
    private $code;

    /** @var int */
    private $level;

    /** @var string */
    private $information;

    /**
     * @param string $code
     * @param int    $level
     * @param string $information
     */
    public function __construct(string $code, int $level, string $information)
    {
        $this->code = $code;
        $this->level = $level;
        $this->information = $information;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return int
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getInformation(): string
    {
        return $this->information;
    }
}
