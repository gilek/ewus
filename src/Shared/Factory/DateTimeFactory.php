<?php

declare(strict_types=1);

namespace Gilek\Ewus\Shared\Factory;

use DateTimeImmutable;
use Gilek\Ewus\Shared\Exception\InvalidDateException;

class DateTimeFactory
{
    /**
     * @throws InvalidDateException
     */
    public function createDateTime(string $dateTime): DateTimeImmutable
    {
        if ($dateTime === 'now') {
            return new DateTimeImmutable();
        }

        $dateObject = DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s.uO', $dateTime);
        if ($dateObject === false || $this->hasDateTimeReportedErrors()) {
            throw new InvalidDateException(sprintf('Can\'t create date from "%s".', $dateTime));
        }

        return $dateObject;
    }

    /**
     * @throws InvalidDateException
     */
    public function createDate(string $date): DateTimeImmutable
    {
        $dateObject = DateTimeImmutable::createFromFormat('!Y-m-dO', $date);
        if ($dateObject === false || $this->hasDateTimeReportedErrors()) {
            throw new InvalidDateException(sprintf('Can\'t create date from "%s".', $date));
        }

        return $dateObject;
    }

    private function hasDateTimeReportedErrors(): bool
    {
        $errors = DateTimeImmutable::getLastErrors();
        if ($errors === false) {
            return false;
        }

        return $errors['warning_count'] > 0 || $errors['error_count'] > 0;
    }
}
