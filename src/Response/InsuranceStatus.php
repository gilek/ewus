<?php
declare(strict_types=1);


namespace Gilek\Ewus\Response;

class InsuranceStatus
{
    /** @var int */
    private $code;

    /** @var bool */
    private $dn;

    /**
     * @param int $code
     * @param bool $dn
     */
    public function __construct(int $code, bool $dn)
    {
        $this->code = $code;
        $this->dn = $dn;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isDn(): bool
    {
        return $this->dn;
    }
}