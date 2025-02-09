<?php

declare(strict_types=1);

namespace Gilek\Ewus\Response;

use DateTimeInterface;

class Operation
{
    public function __construct(
        private readonly string $id,
        private readonly DateTimeInterface $date
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }
}
