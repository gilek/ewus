<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

use DateTimeInterface;

class Operation
{
    /** @var string */
    private $id;

    /** @var DateTimeInterface */
    private $date;

    /**
     * @param string            $id
     * @param DateTimeInterface $date
     */
    public function __construct(string $id, DateTimeInterface $date)
    {
        $this->id = $id;
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}
